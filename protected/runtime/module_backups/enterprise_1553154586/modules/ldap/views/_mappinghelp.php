<p>
    <?= Yii::t('EnterpriseModule.ldap', 'Mapping options:'); ?> 
<ul>
    <li><?= Yii::t('EnterpriseModule.ldap', 'Part of the users base DN (e.g. OU=People,DC=example,DC=com)'); ?></li>
    <li><?= Yii::t('EnterpriseModule.ldap', 'User Memberships (MemberOf, e.g. CN=xyz_space_access,OU=Groups,DC=example,DC=com)'); ?></li>
    <li><?= Yii::t('EnterpriseModule.ldap', 'Attribute value (e.g. street==Some Street <i>or</i> street=~Street)'); ?></li>
</ul>
</p>