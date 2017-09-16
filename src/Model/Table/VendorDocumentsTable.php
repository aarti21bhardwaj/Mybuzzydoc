<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use App\AuditStashPersister\Traits\AuditLogTrait;

/**
 * VendorDocuments Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vendors
 *
 * @method \App\Model\Entity\VendorDocument get($primaryKey, $options = [])
 * @method \App\Model\Entity\VendorDocument newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VendorDocument[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VendorDocument|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VendorDocument patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VendorDocument[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VendorDocument findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VendorDocumentsTable extends Table
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

        $this->table('vendor_documents');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');

        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'filename' => [
                    'path' => Configure::read('FileUpload.uploadPathForVendorDocuments'),
                    'fields' => [
                    'dir' => 'file_path'
                ],
                'nameCallback' => function ($data, $settings) {
                  return time(). $data['name'];
                }
            ],
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->allowEmpty('filename')
            ->add('picture', 'file', ['rule' => ['mimeType', ['image/*', '.doc','.docx','.pdf']]]);

        $validator
            ->allowEmpty('file_path');

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
        $rules->add(function ($entity, $options) {
        // Return a boolean to indicate pass/failure
            if(isset($entity->document_extension)){
                    return in_array(strtolower($entity->document_extension), ['doc','docx','pdf','jpeg','jpg','png']); 
                }
            return false;

            }, 'mimeTypeChecker');

        return $rules;
    }
}
