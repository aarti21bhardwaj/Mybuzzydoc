<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorLocations Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\HasMany $VendorReviews
 *
 * @method \App\Model\Entity\VendorLocation get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorLocation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorLocation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorLocation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorLocation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorLocation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorLocation findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorLocationsTable extends Table
{

    use AuditLogTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('vendor_locations');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReviewRequestStatuses', [
            'foreignKey' => 'vendor_location_id'
        ]);
        $this->hasMany('VendorReviews', [
            'foreignKey' => 'vendor_location_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('address');

         $validator
            ->allowEmpty('fb_url')
            ->add('fb_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);
        $validator
            ->allowEmpty('google_url')
            ->add('google_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);

        $validator
            ->allowEmpty('yelp_url')
            ->add('yelp_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);

        $validator
            ->allowEmpty('ratemd_url')
            ->add('ratemd_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);

        $validator
            ->allowEmpty('healthgrades_url')
            ->add('healthgrades_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);

        $validator
            ->allowEmpty('yahoo_url')
            ->add('yahoo_url', [
                'url_check'=>[
                 'rule'      => ['custom', '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'],
                 'message'   => 'Please enter a valid url.'
               ]
            ]);

        $validator
            ->boolean('is_default')
            ->allowEmpty('is_default');

        $validator
            ->allowEmpty('hash_tag');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));

        return $rules;
    }
}
