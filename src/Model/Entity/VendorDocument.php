<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * VendorDocument Entity
 *
 * @property int $id
 * @property int $vendor_id
 * @property string $name
 * @property string $filename
 * @property string $file_path
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Vendor $vendor
 */
class VendorDocument extends Entity
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

    protected function _getDocumentUrl()
    {
        if(isset($this->_properties['filename']) && !empty($this->_properties['filename'])) {
            $url = Router::url('/vendor_documents/'.$this->_properties['filename'],true);
        }
        return $url;
        
    }

    protected function _getDocumentExtension()
    {
        if(isset($this->_properties['filename']['name'])) {
            $array = explode('.',$this->_properties['filename']['name']);
            return end($array);
        }
        return false;
    }
}