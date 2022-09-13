<?php

/**
 * The provider that connects to the API
 * and perform API-specific methods.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary perform authentication.
 *
 * @since      1.0.0
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
 * @author     Currinda
 */
class Currinda_Auth_User {

    protected $user;

    public function __construct( $user ) {
        $this->user = $user;
    }

    /**
     * check the different types of membership 
     * (standard, corporate, committee, sub-member)
     *
     * @return void
     */
    function check_valid_record() {
        
        if ($this->is_membership_standard()) { return true; }
        if ($this->is_membership_corporate()) { return true; }
        if ($this->is_membership_sub()) { return true; }
        
        return false;
    }

    /**
     * Checks if membership
     *
     * @return boolean
     */
    function is_membership_sub() {
        if (!isset($this->user->Membership->Parent)) { return false; }
        if (!$this->is_expired($this->user->Membership->Parent->ExpiryDate) && 
                ($this->user->Membership->Parent->Status !== "unapproved") && 
                $this->user->Membership->Parent->Checked && 
                !$this->user->Membership->Parent->Expired) {
            return true;
        }
        return false;
    }

    /**
     * Checks if date is expired.
     *
     * @return boolean
     */
    public function is_expired($expiryDate) {
        $timezone = new DateTimeZone(date_default_timezone_get());
        $expiryDate = new DateTime($expiryDate, $timezone);
        $current = new DateTime("now", $timezone);
        if ($expiryDate < $current) {
            return true;
        }
        return false;
    }

    /**
     * Checks if membership is standard
     *
     * @return boolean
     */
    public function is_membership_standard() {
        if (!$this->is_expired($this->user->Membership->ExpiryDate) && 
              ($this->user->Membership->Status !== "unapproved") && 
              $this->user->Membership->Checked && 
              !$this->user->Membership->Expired) {
          return true;
        }
        return false;
    }
    
    /**
     * Checks if membership is corporate
     *
     * @return boolean
     */
    public function is_membership_corporate() {
        foreach ($this->user->CorporateMemberships as $corp_member) {
            if (!$this->is_expired($corp_member->ExpiryDate) && 
                  ($corp_member->Status !== "unapproved") && 
                  $corp_member->Checked &&
                  !$corp_member->Expired) {
              return true;
            }
        }
        return false;
    }

    /**
     * Check if membership is unapproved
     *
     * @return boolean
     */
    function is_membership_unapproved() {
        if ($this->user->Membership->Status === 'unapproved') {
            return true;
        }
        foreach ($this->user->CorporateMemberships as $corp_member) {
            if ($corp_member->Status === 'unapproved') {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if memberships is ecpired
     *
     * @return boolean
     */
    function is_membership_expired() {
        if ($this->user->Membership->Expired) {
            return true;
        }
        foreach ($this->user->CorporateMemberships as $corp_member) {
            if ($corp_member->Expired) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if membership is overdue
     *
     * @return boolean
     */
    function is_membership_overdue() {
        if ($this->user->Membership->Status === 'outstanding') {
            return true;
        }
        foreach ($this->user->CorporateMemberships as $corp_member) {
            if ($corp_member->Status === 'outstanding') {
                return true;
            }
        }
        return false;
    }
}