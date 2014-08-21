<?php
/**
 * ContributorContact
 *
 * @package ContributorContact
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The ContributorContact index controller class.
 *
 * @package ContributorContact
 */
class ContributorContact_IndexController extends Omeka_Controller_AbstractActionController
{    

    private $_users;
    private $_emails;
    private $_emailString;

    /**
     * The default action to display the import from and process it.
     *
     * This action runs before loading the main import form. It 
     * processes the form output if there is any, and populates
     * some variables used by the form.
     *
     * @param void
     * @return void
     */
    public function indexAction()
    {
        $db=get_db();
        $sql = "SELECT DISTINCT user.email,user.id,user.username,user.name FROM ".$db->User." AS user"
            ." LEFT JOIN (".$db->Item." AS item,"
            .$db->ContributionContributedItem." AS citem )"
            ." ON ( user.id = item.owner_id AND item.id = citem.item_id)";
        $db = get_db();
        $reply = $db->query($sql);

        while(is_array($user = $reply->fetch())) {
            $this->_users[] = $user;
            $this->_emails[] = $user['email'];
            $this->_emailString .=  $user['email'] . ';';
        }

        $this->view->users = $this->_users;
        $this->view->emails = $this->_emails;
        $this->view->emailString =  $this->_emailString;
        
    }

    /**
     * An action to return email addresses by ajax.
     *
     * @param void
     * @return void
     */
    public function emailsAction()
    {
        $db=get_db();
        $sql = "SELECT DISTINCT user.email,user.id,user.username,user.name FROM ".$db->User." AS user"
            ." LEFT JOIN (".$db->Item." AS item,"
            .$db->ContributionContributedItem." AS citem )"
            ." ON ( user.id = item.owner_id AND item.id = citem.item_id)";
        $db = get_db();
        $reply = $db->query($sql);

        while(is_array($user = $reply->fetch())) {
            $this->_emails[] = str_replace('@',' at ',$user['email']);
        }

        die(json_encode($this->_emails));
    }
}
