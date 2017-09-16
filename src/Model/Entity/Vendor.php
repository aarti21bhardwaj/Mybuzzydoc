<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Vendor Entity
 *
 * @property int $id
 * @property string $org_name
 * @property string $reward_url
 * @property bool $is_legacy
 * @property int $people_hub_identifier
 * @property bool $status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $is_deleted
 * @property float $min_deposit
 * @property float $threshold_value
 * @property int $template_id
 * @property string $image_path
 * @property $image_name
 * @property int $sandbox_people_hub_identifier
 *
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\CreditCardCharge[] $credit_card_charges
 * @property \App\Model\Entity\EmailLayout[] $email_layouts
 * @property \App\Model\Entity\EmailTemplate[] $email_templates
 * @property \App\Model\Entity\GiftCoupon[] $gift_coupons
 * @property \App\Model\Entity\LegacyRedemption[] $legacy_redemptions
 * @property \App\Model\Entity\LegacyReward[] $legacy_rewards
 * @property \App\Model\Entity\Promotion[] $promotions
 * @property \App\Model\Entity\ReferralLead[] $referral_leads
 * @property \App\Model\Entity\ReferralTemplate[] $referral_templates
 * @property \App\Model\Entity\Referral[] $referrals
 * @property \App\Model\Entity\ReviewSetting[] $review_settings
 * @property \App\Model\Entity\Tier[] $tiers
 * @property \App\Model\Entity\ReferralTier[] $referral_tiers
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\VendorDepositBalance[] $vendor_deposit_balances
 * @property \App\Model\Entity\VendorEmailSetting[] $vendor_email_settings
 * @property \App\Model\Entity\VendorLocation[] $vendor_locations
 * @property \App\Model\Entity\VendorPlan[] $vendor_plans
 * @property \App\Model\Entity\VendorPromotion[] $vendor_promotions
 * @property \App\Model\Entity\VendorRedeemedPoint[] $vendor_redeemed_points
 * @property \App\Model\Entity\VendorReferralSetting[] $vendor_referral_settings
 * @property \App\Model\Entity\VendorSetting[] $vendor_settings
 * @property \App\Model\Entity\VendorSurvey[] $vendor_surveys
 * @property \App\Model\Entity\VendorMilestone $vendor_milestone
 * @property \App\Model\Entity\VendorPatient $vendor_patient
 * @property \App\Model\Entity\VendorDocument[] $vendor_documents
 * @property \App\Model\Entity\ReferralTierAward[] $referral_tier_awards
 * @property \App\Model\Entity\OldBuzzydocVendor $old_buzzydoc_vendor
 * @property \App\Model\Entity\VendorCardSeries[] $vendor_card_series
 */
class Vendor extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
    protected $_hidden = ['sandbox_people_hub_identifier'];
    protected $_virtual = ['image_url'];
    protected function _getImageUrl()
    {

        if(isset($this->_properties['image_name']) && is_array($this->_properties['image_name'])){
            $this->_properties['image_name'] = '';
        }
        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/vendors_logos/'.$this->_properties['image_name'],true);
        }else{
            $url = Router::url('/img/default-img.jpeg',true);
        }
        return $url;

    }

}
