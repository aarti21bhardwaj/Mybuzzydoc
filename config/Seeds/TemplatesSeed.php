<?php
use Migrations\AbstractSeed;

/**
 * Templates seed.
 */
class TemplatesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    ['name' => 'orthodontics (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2000,"referree_award_points":0,"status":true,"referral_level_name":"Referral (Thank you]"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'optometry (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'general dentistry (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2000,"referree_award_points":0,"status":true,"referral_level_name":"Referral (Thank you]"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'audiology (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2000,"referree_award_points":0,"status":true,"referral_level_name":"Referral (Thank you]"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'plastic surgery (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2000,"referree_award_points":0,"status":true,"referral_level_name":"Referral (Good]"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'veterinary (good]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2000,"referree_award_points":0,"status":true,"referral_level_name":"Referral (Thank You]"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'orthodontics (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":2500,"referree_award_points":0,"status":true,"referral_level_name":"Patient Referral Ortho"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'general dentistry (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => '[{"referrer_award_points":0,"referree_award_points":0,"status":false,"referral_level_name":"Patient Referral"}]','tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'plastics (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'optometry (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'veterinary (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'audioloy (better]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'plastics (best]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '[{"lowerbound":0,"upperbound":1500,"multiplier":0.01,"name":"Level 1"},{"lowerbound":1501,"upperbound":3000,"multiplier":0.015,"name":"Level 2"},{"lowerbound":3001,"upperbound":5000,"multiplier":0.02,"name":"Level 3"}]','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'optometry (best]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '[{"lowerbound":0,"upperbound":350,"multiplier":0.01,"name":"Level 1"},{"lowerbound":351,"upperbound":500,"multiplier":0.01,"name":"Level 2"},{"lowerbound":501,"upperbound":700,"multiplier":0.01,"name":"Level 3"}]','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'veterinary (best]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'general dentistry (best]','review' => '{"review_points":10,"rating_points":10,"fb_points":100,"gplus_points":250,"yelp_points":250,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'orthodontics (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'general dentistry (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'plastics (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'optometry (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'veterinary (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'audiology (better] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'plastics (best] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '[{"lowerbound":0,"upperbound":1500,"multiplier":0.02,"name":"Level 1"},{"lowerbound":1501,"upperbound":3000,"multiplier":0.025,"name":"Level 2"},{"lowerbound":3001,"upperbound":5000,"multiplier":0.03,"name":"Level 3"}]','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'optometry (best] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '[{"lowerbound":0,"upperbound":350,"multiplier":0.02,"name":"Level 1"},{"lowerbound":351,"upperbound":500,"multiplier":0.02,"name":"Level 2"},{"lowerbound":501,"upperbound":700,"multiplier":0.02,"name":"Level 3"}]','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'veterinary (best] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')],
                    ['name' => 'general dentistry (best] aggressive','review' => '{"review_points":50,"rating_points":50,"fb_points":250,"gplus_points":500,"yelp_points":500,"ratemd_points":250,"yahoo_points":0,"healthgrades_points":250}','referral' => NULL,'tier' => '','created' => date('Y-m-d H:i:s'),'modified' => date('Y-m-d H:i:s')]

        ];

        $table = $this->table('templates');
        $table->insert($data)->save();
    }
}
