<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace humhub\modules\enterprise\modules\ldap\authclient;

use humhub\modules\user\models\GroupUser;
use humhub\modules\user\models\User;
use Yii;

/**
 * LDAP Authentication
 *
 * @todo create base ldap authentication, to bypass ApprovalByPass Interface
 */
class ZendLdapClientEnterprise extends \humhub\modules\user\authclient\ZendLdapClient
{

    /**
     * @var string|null ldap attribute name which holds the user profile image
     */
    public $profileImageAttribute = null;

    /**
     * @var boolean if enabled only LDAP profile images will be used - otherwise import when no image exists
     */
    public $forceProfileImage = true;

    /**
     * @var \humhub\modules\enterprise\modules\ldap\models\Space[] Map
     */
    private static $_spaceMappings = null;

    /**
     * @var \humhub\modules\enterprise\modules\ldap\models\Group[] Map
     */
    private static $_groupMappings = null;

    /**
     * @var string the temp directory
     */
    protected $tempDirectory;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->on(self::EVENT_UPDATE_USER, array($this, 'onUpdateUser'));
        $this->on(self::EVENT_CREATE_USER, array($this, 'onUpdateUser'));

        // Create temporary directory for image imports
        $this->tempDirectory = Yii::getAlias('@runtime/temp');
        if (!is_dir($this->tempDirectory)) {
            mkdir($this->tempDirectory);
        }

        parent::init();
    }

    /**
     * Ensure group and space mapping
     *
     * @param \yii\web\UserEvent $event
     */
    public function onUpdateUser($event)
    {
        $user = $event->identity;
        // Load space mappings configured by humhub
        if (self::$_spaceMappings === null) {
            self::$_spaceMappings = \humhub\modules\enterprise\modules\ldap\models\Space::find()->with(['space'])->all();
        }

        // Load group mappings configured by humhub
        if (self::$_groupMappings === null) {
            self::$_groupMappings = \humhub\modules\enterprise\modules\ldap\models\Group::find()->all();
        }

        $attributes = $this->getUserAttributes();

        $this->updateSpaceMemberships($user, $attributes);
        $this->updateGroupMemberships($user, $attributes);
        $this->updateProfileImage($user, $attributes);
    }

    protected function updateProfileImage(\humhub\modules\user\models\User $user, $attributes)
    {

        // Check if image import is enabled
        if ($this->profileImageAttribute === null) {
            return;
        }

        // Check if user has already an image && overwrite is enabled
        if ($user->profileImage->hasImage() && !$this->forceProfileImage) {
            return;
        }

        // Check if LDAP image is set
        if (!isset($attributes[$this->profileImageAttribute])) {
            return;
        }

        $ldapImageFile = $user->profileImage->getPath('_ldap');

        // Check if image has changed
        if (file_exists($ldapImageFile)) {
            if (file_exists($user->profileImage->getPath('_org')) && md5_file($ldapImageFile) == md5($attributes[$this->profileImageAttribute])) {
                return;
            }
        }

        file_put_contents($ldapImageFile, $attributes[$this->profileImageAttribute]);
        $user->profileImage->setNew($ldapImageFile);
    }

    protected function updateSpaceMemberships(User $user, $attributes)
    {
        #Yii::error("Got following LDAP memberships for user " . $user->displayname . ": " . print_r($memberships, 1));
        // Update user's space memberships
        $userLdapSpaceIds = [];
        foreach (self::$_spaceMappings as $spaceMapping) {
            if (self::matchMapping($spaceMapping->dn, $user, $attributes)) {

                if ($spaceMapping->space === null) {
                    Yii::error('Space mapping invalid. Space mapping with id ' . $spaceMapping->id . ' is invalid!', 'ldap');
                    continue;
                }

                // add user as member of space.
                $spaceMapping->space->addMember($user->id);
                $membership = $spaceMapping->space->getMembership($user->id);
                $membership->authclient_id = $this->getId();
                $membership->save();
                Yii::info('Added user ' . $user->displayName . ' to space ' . $spaceMapping->space->name . '. (LDAP Space Mapping)');

                // Store mappings to revoke
                $userLdapSpaceIds[] = $spaceMapping->space_id;
            }
        }

        // Get all current user group memberships handled by LDAP
        $spaceMembershipsLdap = \humhub\modules\space\models\Membership::find()->with('space')->where(['authclient_id' => $this->getId(), 'user_id' => $user->id])->all();
        // Remove memberships where the ldap mapping no longer exists for the user 
        foreach ($spaceMembershipsLdap as $spaceMembershipLdap) {
            if (!in_array($spaceMembershipLdap->space_id, $userLdapSpaceIds)) {
                $spaceMembershipLdap->delete();
                Yii::info('Removing user ' . $user->displayName . ' from space ' . $spaceMembershipLdap->space->name . '. (No LDAP Space Mapping anymore)');
            }
        }
    }

    protected function updateGroupMemberships(User $user, $attributes)
    {
        // Make sure user is added to all groups
        $userLdapGroupIds = [];
        foreach (self::$_groupMappings as $groupMapping) {

            if (self::matchMapping($groupMapping->dn, $user, $attributes)) {

                if ($groupMapping->group === null) {
                    Yii::error('Group mapping invalid. Group mapping with id ' . $groupMapping->id . ' is invalid!', 'ldap');
                    continue;
                }

                if (!$groupMapping->group->isMember($user)) {
                    $newGroupUser = new GroupUser();
                    $newGroupUser->user_id = $user->id;
                    $newGroupUser->group_id = $groupMapping->group_id;
                    $newGroupUser->is_group_manager = false;
                    $newGroupUser->authclient_id = $this->getId();

                    if ($newGroupUser->save()) {
                        Yii::info('Added user ' . $user->displayName . ' to group ' . $groupMapping->group->name . '. (LDAP Group Mapping)');
                    } else {
                        Yii::error('Could not add user ' . $user->displayName . ' to group ' . $groupMapping->group->name . '. (LDAP Group Mapping)');
                    }
                }
                $userLdapGroupIds[] = $groupMapping->group_id;
            }
        }

        // Get current user group memberships handled by LDAP
        $groupUsersLdap = GroupUser::find()->with('group')->where(['authclient_id' => $this->getId(), 'user_id' => $user->id])->all();
        foreach ($groupUsersLdap as $groupUserLdap) {
            if (!in_array($groupUserLdap->group_id, $userLdapGroupIds)) {
                $groupUserLdap->group->removeUser($user);
                Yii::info('Removing user ' . $user->displayName . ' from group ' . $groupUserLdap->group->name . '. (No LDAP Group Mapping anymore)');
            }
        }
    }

    /**
     * Matches a mapping against the user ldap attributes, this can be done by different possibilities.
     *
     * The mapping is found in:
     * - the attributes 'memberof' array
     * - is part of the 'dn' attribute
     *
     * Additionally there is also a special syntax to directly match attributes:
     * - attributeName==attributeValue (e.g. city==Munich)
     * - attributeName=~IT (e.g. some IT Department, department=IT Services)
     *
     * @param string $mapping
     * @param User $user
     * @param array $attributes
     * @return boolean if the mapping matches
     */
    protected static function matchMapping($mapping, User $user, $attributes)
    {
        // Check if mapping is in membership array
        if (isset($attributes['memberof']) && is_array($attributes['memberof'])) {
            if (in_array(strtolower($mapping), array_map('strtolower', $attributes['memberof']))) {
                return true;
            }
        }


        // Check if mapping is part of the user ldap record DN
        if (!isset($attributes['dn']) && isset($attributes['distinguishedname'])) {
            $attributes['dn'] = $attributes['distinguishedname'];
        }
        if (isset($attributes['dn']) && strpos(strtolower($attributes['dn']), strtolower($mapping)) !== false) {
            return true;
        }

        // Check if mapping is found as user ldap attribute (==)
        if (strpos($mapping, '==') !== false) {
            list($name, $value) = explode('==', $mapping, 2);
            $name = strtolower($name);

            if (is_array($value) || is_array($attributes[$name])) {
                return false;
            }

            if (isset($attributes[$name]) && trim($attributes[$name]) == trim($value)) {
                return true;
            }
        }

        // Check if mapping is found as user ldap attribute value part (=~)
        if (strpos($mapping, '=~') !== false) {
            list($name, $value) = explode('=~', $mapping, 2);
            $name = strtolower($name);
            if (isset($attributes[$name]) && strpos($attributes[$name], trim($value)) !== false) {
                return true;
            }
        }

        return false;
    }

}
