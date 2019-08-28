<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\controllers;

//namespace humhub\modules\user\widgets;

use Yii;
use yii\web\HttpException;
use yii\db\Expression;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\space\widgets\ListBox;
use humhub\components\behaviors\AccessControl;
use humhub\modules\user\permissions\ViewAboutPage;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\content\models\ContentContainer;
use yii\httpclient\Client;
use humhub\modules\user\models\OrcidIdTbl;

/**
 * OrcidController is responsible for all user profiles.
 * Also the following functions are implemented here.
 *
 * @author Luke
 * @package humhub.modules_core.user.controllers
 * @since 0.5
 */
class OrcidController extends ContentContainerController {

    public $client_id = "APP-QCP04G0DPF43TFNX";
    public $client_secret = "de960cb3-8e9a-4fa6-9ecd-8751f180f3d8";
    public $REDIRECT_URI = "https://research-hub.social/index.php?r=user/orcid/orcid_redirected";
    public $apiBaseUrl = 'https://pub.orcid.org/v2.1';
    public $attributes_2d_array = array();
    public $attri_2d_array_works = array();
    public $elements = null, $orcid_id = null;
//    public $tokken_url = "https://orcid.org/oauth/token?" . "client_id=" . '$client_id' .
//            "&client_secret=" . '$client_secret' . "&grant_type=" . "authorization_code" . "&redirect_uri="
//            . '$REDIRECT_URI';
//    const authorize_url  = "https://orcid.org/oauth/authorize?client_id=". $client_id. "&response_type=code&scope=/authenticate". "&redirect_uri=" . $REDIRECT_URI;
//    public $authorize_url = "https://orcid.org/oauth/authorize?client_id=". '$authorize_url'. "&response_type=code&scope=/authenticate". "&redirect_uri=" . '$REDIRECT_URI';

    public $authorize_url = "";
    public $token_url = "";
    public $code = "";
    public $count = 0;
    public $count_works_attri = 0;
    public $single_row_arr = array();
    public $single_row_arr_works = array();

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'acl' => [
                'class' => AccessControl::class,
                'guestAllowedActions' => ['index', 'stream', 'about']
            ]
        ];
    }
    
    //public function get_authorize_url()
    //{
    //    $session = Yii::$app->session;
    //    $this->authorize_url = "https://orcid.org/oauth/authorize?client_id=" . $this->client_id . "&response_type=code&scope=/authenticate" . "&redirect_uri=" . urlencode($this->REDIRECT_URI . "&cguid=" . $session->get('cguid'));

   //        return $this->authorize_url;
  //  }

    public function get_authorize_url() {
        $this->authorize_url = "https://orcid.org/oauth/authorize?client_id=" . $this->client_id . "&response_type=code&scope=/authenticate" . "&redirect_uri=" . $this->REDIRECT_URI;

        return $this->authorize_url;
    }

    public function get_orcid_id_from_session() {

        $session = Yii::$app->session;
        $orcid_id = $session->get('orcid_id');

        return $orcid_id;
    }

    public function set_orcid_id_in_session() {

        $session = Yii::$app->session;
        $this->code = $session->get('code');
//        echo "code=". $this->code ;
//        die();


        $this->token_url = "https://orcid.org/oauth/token?" . "client_id=" . $this->client_id .
                "&client_secret=" . $this->client_secret .
                "&grant_type=" . "authorization_code" .
                "&redirect_uri=" . $this->REDIRECT_URI .
                "&code=" . $this->code;


        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->token_url)
                ->send();

        $arrayData = json_decode($response->content, true);
        //print_r($arrayData);
        //die();
        if (isset($arrayData["orcid"])) {
//            echo "orcid=" . $arrayData["orcid"];  // Output: 65
//            die();
            $session = Yii::$app->session;
            $session->set('orcid_id', $arrayData["orcid"]);

//            // orcid_id insert into database
//            $email = Yii::$app->user->getIdentity()->email;
//            $this->insertIntoDb($email,$arrayData["orcid"]);
        }
    }

    public function insertIntoDb($email, $orcid_id) {
        $mOrcidIdTbl = new OrcidIdTbl();
        $mOrcidIdTbl->email = $email;
        $mOrcidIdTbl->orcid_id = $orcid_id;
        $mOrcidIdTbl->save();
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'stream' => [
                'class' => ContentContainerStream::class,
                'mode' => ContentContainerStream::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }

    public function checkAlreadyHaveOrcid_id_in_db() {
        $orcid_id = "";
        $email = Yii::$app->user->getIdentity()->email;

        $mOrcidIdTbl = OrcidIdTbl::findOne([
                    'email' => $email,
        ]);

        if (isset($mOrcidIdTbl->orcid_id)) {
            $orcid_id = $mOrcidIdTbl->orcid_id;
        }

        return $orcid_id;
    }

    public function actionOrcid_details() {
        if (!$this->contentContainer->permissionManager->can(new ViewAboutPage())) {
            throw new HttpException(403, 'Forbidden');
        }

        $orcid_id = $this->checkAlreadyHaveOrcid_id_in_db();


        // if orcid id already in database
        if ($orcid_id != "") {
            // set orcid_id in session
            $session = Yii::$app->session;
            $session->set('orcid_id', $orcid_id);

            // change here
            //$attributes_education = $this->get_data_from_education_api("0000-0002-3136-307X"); // Pipek
            //$attributes_works = $this->get_data_from_works_api("0000-0002-3136-307X"); // Pipek
//        $attributes = $this->get_data_from_education_api("0000-0002-6172-4681"); // korn
//        $attributes = $this->get_data_from_api("0000-0003-1220-4695"); // istiaq
            $attributes_education = $this->get_data_from_education_api($this->get_orcid_id_from_session()); // for all
            $attributes_works = $this->get_data_from_works_api($this->get_orcid_id_from_session()); // for all

            $render_view = $this->render('orcid_details', array('user' => $this->contentContainer, 'attributes_education' => $attributes_education , 'attributes_works' =>$attributes_works) );
        } else {
            $render_view = $this->render('orcid_login', ['user' => $this->contentContainer]);
        }

        return $render_view;
    }

    public function actionOrcid_fetch_data() {
        if (!$this->contentContainer->permissionManager->can(new ViewAboutPage())) {
            throw new HttpException(403, 'Forbidden');
        }

        $this->set_orcid_id_in_session();


        // orcid_id insert into database
        $email = Yii::$app->user->getIdentity()->email;
        $orcid_id = $this->get_orcid_id_from_session();
        $this->insertIntoDb($email, $orcid_id);


//        echo 'orcid_id=' . $this->get_orcid_id_from_session();
        $attributes = $this->get_data_from_education_api($this->get_orcid_id_from_session());

//        die();
//        return $this->render('orcid_details', ['user' => $this->contentContainer]);
        return $this->render('orcid_details', array('user' => $this->contentContainer, 'attributes' => $attributes));
    }

    public function get_data_from_works_api($orcid_id) {

        $parser = xml_parser_create();
        xml_set_element_handler($parser, array($this, 'startElementsWorks'), array($this, 'endElementsWorks'));
        xml_set_character_data_handler($parser, array($this, 'characterDataWorks'));
// open xml file
        if (!($handle = fopen($this->apiBaseUrl . '/' . $orcid_id . '/works', "r"))) {
            die("could not open XML input");
        }
        while ($data = fread($handle, 4096)) { // read xml file {
            xml_parse($parser, $data);
        }  // start parsing an xml document 
        xml_parser_free($parser); // deletes the parser

        $attributes = $this->attri_2d_array_works;
//        echo 'attributes works_api =';
//        print_r($attributes);
//        exit;
        return $attributes;
    }

    // Called on the text between the start and end of the tags
    function characterDataWorks($parser, $data) {
        global $elements;


//        echo "characterDataWorks=". $elements." =" . $data . "<br>";
        if (!empty($data) && isset($data) && $data != "") {
            if ($elements == 'COMMON:TITLE' || $elements == 'WORK:TYPE' || $elements == 'COMMON:EXTERNAL-ID-VALUE') {
                $data = trim($data);
                $this->count_works_attri++;

                if ($elements == 'COMMON:TITLE') {
                    $this->single_row_arr_works["COMMON:TITLE"] = $data;
                }
                if ($elements == 'WORK:TYPE') {
                    $this->single_row_arr_works["WORK:TYPE"] = $data;
                }
                
//                $COMMON_EXTERNAL_ID_VALUE_counter =0;
                
                if ($elements == 'COMMON:EXTERNAL-ID-VALUE') {
                    $this->single_row_arr_works["COMMON:EXTERNAL-ID-VALUE"] = $data;
                }
//                if ($elements == 'EDUCATION:ROLE-TITLE') {
//                    $this->tutors["username"] = $this->tutors['COMMON:NAME']. ".".$this->tutors['COMMON:CITY'];
//                }
//                $this->tutors[$elements] = trim($data);
//                echo "COMMON:NAME==".$tutors['COMMON:NAME' ] ;
//                echo "characterDataWorks=" . $elements . " =" . $data . "<br>";
            }

            if ($this->count_works_attri >= 4) {
//                 echo 'attributes works_api =';
//                print_r($this->single_row_arr_works);
//                echo '<br><br>';
//                exit;
                 
                $this->count_works_attri = 0;
                array_push($this->attri_2d_array_works, $this->single_row_arr_works);
            }
        }
    }

    // Called to this function when tags are opened 
    function startElementsWorks($parser, $name, $attrs) {
        global $elements;
        if (!empty($name)) {
            if ($name == 'COURSE') {
// creating an array to store information
                $this->attri_2d_array_works [] = array();
            }
            $elements = $name;
        }
    }

// Called to this function when tags are closed 
    function endElementsWorks($parser, $name) {
        global $elements;
        if (!empty($name)) {
            $elements = null;
        }
    }

    public function get_data_from_education_api($orcid_id) {

        $parser = xml_parser_create();
        xml_set_element_handler($parser, array($this, 'startElementsEducation'), array($this, 'endElementsEducation'));
        xml_set_character_data_handler($parser, array($this, 'characterDataEducation'));
// open xml file
        if (!($handle = fopen($this->apiBaseUrl . '/' . $orcid_id . '/educations', "r"))) {
            die("could not open XML input");
        }
        while ($data = fread($handle, 4096)) { // read xml file {
            xml_parse($parser, $data);
        }  // start parsing an xml document 
        xml_parser_free($parser); // deletes the parser
//        $i = 1;
//        foreach ($this->attributes_2d_array as $attributes_values) {
//
//            if (isset($attributes_values['COMMON:NAME'])) {
//                echo "GIVEN-NAMES: " . $attributes_values['COMMON:NAME'] . '<br/>';
//            }
//            if (isset($attributes_values['COMMON:CITY'])) {
//                echo "FAMILY-NAME - " . $attributes_values['COMMON:CITY'] . '<br/>';
//            }
//            if (isset($attributes_values['EDUCATION:ROLE-TITLE']) && $attributes_values['EDUCATION:ROLE-TITLE'] != NULL && $attributes_values['EDUCATION:ROLE-TITLE'] != " ") {
//                echo "EMAIL - " . $attributes_values['EDUCATION:ROLE-TITLE'] . '<br/>';
//            }
//            $i++;
//        }
//        
//        $attributes = json_encode($this->tutors);
        $attributes = $this->attributes_2d_array;
//        echo 'attributes=';
//        print_r($attributes);
//        exit;
        return $attributes;
    }

    // Called to this function when tags are opened 
    function startElementsEducation($parser, $name, $attrs) {
        global $elements;
        if (!empty($name)) {
            if ($name == 'COURSE') {
// creating an array to store information
                $this->attributes_2d_array [] = array();
            }
            $elements = $name;
        }
    }

// Called to this function when tags are closed 
    function endElementsEducation($parser, $name) {
        global $elements;
        if (!empty($name)) {
            $elements = null;
        }
    }

    // Called on the text between the start and end of the tags
    function characterDataEducation($parser, $data) {
        global $elements;

//        echo $elements." =" . $data . "<br>";
        if (!empty($data) && isset($data) && $data != "") {
            if ($elements == 'COMMON:NAME' || $elements == 'COMMON:CITY' || $elements == 'EDUCATION:ROLE-TITLE') {
                $data = trim($data);
                $this->count++;

                if ($elements == 'COMMON:NAME') {
                    $this->single_row_arr["COMMON:NAME"] = $data;
                }
                if ($elements == 'COMMON:CITY') {
                    $this->single_row_arr["COMMON:CITY"] = $data;
                }
                if ($elements == 'EDUCATION:ROLE-TITLE') {
                    $this->single_row_arr["EDUCATION:ROLE-TITLE"] = $data;
                }
//                echo "after = ".$elements." =" . $data . "<br>";
            }

            if ($this->count >= 3) {
                $this->count = 0;
                array_push($this->attributes_2d_array, $this->single_row_arr);
            }
        }
    }

    public function actionOrcid_redirected() {
        $code = Yii::$app->request->get('code', Yii::$app->request->get('code'));

        $session = Yii::$app->session;
        $session->set('code', $code);
        $cguid = $session->get('cguid');
        
//        echo 'code=' . $code;
//        die();

        return $this->redirect('https://research-hub.social/index.php?r=user%2Forcid%2Forcid_fetch_data&cguid=' . $cguid);
    }

    public function actionOrcid_link() {

        return $this->redirect($this->get_authorize_url());
    }

}
