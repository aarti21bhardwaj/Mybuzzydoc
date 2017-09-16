<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
// use Cake\Localized\Validation\UsValidation;
use Cake\Database\Expression\Comparison;
use Cake\Event\Event;
use Cake\Routing\Router;
use App\AuditStashPersister\Traits\AuditLogTrait;
/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\HasMany $AuthorizeNetProfiles
 * @property \Cake\ORM\Association\HasMany $CreditCardCharges
 * @property \Cake\ORM\Association\HasMany $LegacyRedemptions
 * @property \Cake\ORM\Association\HasMany $PromotionAwards
 * @property \Cake\ORM\Association\HasMany $ReferralAwards
 * @property \Cake\ORM\Association\HasMany $ResetPasswordHashes
 * @property \Cake\ORM\Association\HasMany $ReviewAwards
 * @property \Cake\ORM\Association\HasMany $ReviewRequestStatuses
 * @property \Cake\ORM\Association\HasMany $UserOldPasswords
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('AuditStash.AuditLog');

        $this->addBehavior('Muffin/Trash.Trash', [
            'field' => 'is_deleted'
            ]);
        $this->addBehavior('Timestamp');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('VendorLocations', [
            'foreignKey' => 'vendor_location_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AuthorizeNetProfiles', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('CreditCardCharges', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('GiftCouponAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('LegacyRedemptions', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PromotionAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ReferralAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('TierAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ReferralTierAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ManualAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('MilestoneLevelAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ResetPasswordHashes', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('SurveyAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ReviewAwards', [

            'foreignKey' => 'user_id'
        ]);
        //Survey Awards
        $this->hasMany('SurveyAwards', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ReviewRequestStatuses', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserOldPasswords', [
            'foreignKey' => 'user_id'
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
        ->requirePresence('first_name', 'create')
        ->notEmpty('first_name', 'Please enter your First Name');

        $validator
        ->requirePresence('last_name', 'create')
        ->notEmpty('last_name', 'Please enter your Last Name');

        $validator
        ->requirePresence('username', 'create')
        ->notEmpty('username', 'Username cannot be empty')
        ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table','message' => 'Username already in use.']);

        $validator
        ->email('email')
        ->requirePresence('email', 'create')
        ->notEmpty('email', 'Please provide a Email');
        // ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        /*$validator
        ->allowEmpty('phone');*/
        $validator
            ->requirePresence('phone', 'create')
            ->notEmpty('phone')
            ->add('phone', [
                'minLength' => [
                'rule' => ['minLength', 10],
                'last' => true,
                'message' => 'Invalid phone number. Min length is 10.'
                ],
                'maxLength' => [
                'rule' => ['maxLength', 15],
                'message' => 'Phone number can have max length 15.'
                ]
                ]);

        $validator
        ->requirePresence('password', 'create')
        ->notEmpty('password', 'Password cannot be empty')
        ->add('password', [
            'password_check'=>[
             'rule'      => [$this, 'passwordHippaCheck'],
             'message'   => 'only numbers 0-9, alphabets a-z A-Z and special characters ~!@#$%^*&;?.+_ are allowed'
           ]
        ]);

        $validator
            ->uuid('uuid')
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid');

        $validator
            ->boolean('status')
            ->allowEmpty('status');

        $validator
            ->dateTime('is_deleted')
            ->allowEmpty('is_deleted');

        $validator
            ->boolean('idle_time')
            ->allowEmpty('idle_time');

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
        $rules->add($rules->isUnique(['username']));
        // $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['vendor_id'], 'Vendors'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }
    public function afterSave($event, $entity){
        if($entity->isNew())
        {
            $url = Router::url('/', true);
            $entity->link = $url;
            $event = new Event('User.afterCreate', $this, [
              'arr' => [
                'hashData' => $entity,
                'eventId' => 1, //give the event_id for which you want to fire the email
                'vendor_id' => $entity->vendor_id,
              ]
            ]);
        $this->eventManager()->dispatch($event);
        }
    }

    public function beforeMarshal( \Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options){
       if (!isset($data['uuid'])) {
           $data['uuid'] = Text::uuid();
       }

    }

   public function findWithDisabled(Query $query, array $options)
    {
        $field = 'Users.status';

        return $query->where(['OR' => [
            $query->newExpr()->add([$field => 1]),
            $query->newExpr()->add([$field =>  0]),
        ]]);

    }

    public function passwordHippaCheck($value, $context) {
      if( preg_match("/^[A-Za-z0-9~!@#$%^*&;?.+_]{8,}$/", $value)){
        return TRUE;
      }else{
        return FALSE;
      }
    }

   public function beforeFind(\Cake\Event\Event $event, Query $query, \ArrayObject $options, $primary)
    {
        $field = 'Users.status';
        $check = false;
        $query->traverseExpressions(function ($expression) use (&$check, $field) {
            if ($expression instanceof Comparison) {
                !$check && $check = $expression->getField() === $field;
            }
        });

        if ($check) {
            return;
        }
        $query->andWhere($query->newExpr()->add([$field => 1]));
    }
}
