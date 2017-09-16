<?php
namespace App\Controller\PatientPortalApis;

use App\Controller\PatientPortalApis\ApiController;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Cache\Cache;
use Cake\Controller\Component\CookieComponent;
use Cake\Collection\Collection;
use Cake\Utility\Inflector;
/**
* Vendors Controller
*
* @property \App\Model\Table\VendorsTable $Vendors
*/
class VendorsController extends ApiController
{

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
  }

  /**
  * Index method
  *
  * @return \Cake\Network\Response|null
  */
  public function index()
  {
    $buzzyVendorList = [
      ["clinic"=>"Lamparski Orthobics","patient_portal_url"=>"http://rewards.lamparski.com"],
      ["clinic"=>"Orthozone","patient_portal_url"=>"http://rewards.singletonortho.com"],
      ["clinic"=>"Seidner Dentistry","patient_portal_url"=>"http://rewards.randolphnjdentist.com"],
      ["clinic"=>"TLC for Smiles","patient_portal_url"=>"http://rewards.tlcforsmiles.com"],
        ["clinic"=>"Hurley & Volk Orthodontics","patient_portal_url"=>"http://rewards.hurleyvolkortho.com/"],
        ["clinic"=>"Crawford Orthodontics","patient_portal_url"=>"http://rewards.drcrawfordortho.com"],
          ["clinic"=>"Olsen Orthodontics","patient_portal_url"=>"http://rewards.olsenbraces.com"],
          ["clinic"=>"Forest Orthodontics & Pediatric Dentistry","patient_portal_url"=>"http://rewards.forestorthopedo.com"],
          ["clinic"=>"Hull & Coleman Orthodontics","patient_portal_url"=>"http://rewards.hullandcoleman.com"],
          ["clinic"=>"Rafati Orthodontics","patient_portal_url"=>"http://rewards.rafatiorthodontics.com"],
          ["clinic"=>"Dabney Orthodontics","patient_portal_url"=>"http://rewards.drdabney.com"],
          ["clinic"=>"Edney Orthodontics","patient_portal_url"=>"http://rewards.edneyortho.com/"],
          ["clinic"=>"Austin Orthodontics","patient_portal_url"=>"http://rewards.draustinsmiles.com"],
          ["clinic"=>"Holbert Family Orthodontics","patient_portal_url"=>"http://rewards.HolbertBraces.com/"],
          ["clinic"=>"James Lopez Pediatric Dentistry & Orthodontics","patient_portal_url"=>"http://rewards.columbuskidsdentist.com"],
          ["clinic"=>"Tomas E Moore Orthodontics","patient_portal_url"=>"http://rewards.smilemoore.com"],
          ["clinic"=>"M and M Orthodontics","patient_portal_url"=>"http://rewards.mandmorthodontics.com"],
          ["clinic"=>"Katz Orthodontics","patient_portal_url"=>"http://rewards.katzorthodontics.com"],
          ["clinic"=>"Smiles by Smaha Orthodontics","patient_portal_url"=>"http://rewards.smilesbysmaha.com/"],
          ["clinic"=>"East Gate Orthodontics","patient_portal_url"=>"http://rewards.eastgateorthodontics.com"],
          ["clinic"=>"Bauer Dentistry & Orthodontics","patient_portal_url"=>"http://rewards.bauersmiles.com"],
          ["clinic"=>"Ardsley Orthodontics","patient_portal_url"=>"http://rewards.ardsleyorthodontics.com"],
          ["clinic"=>"Georgetown Orthodontics","patient_portal_url"=>"http://rewards.GtownOrtho.com/"],
          ["clinic"=>"Ashby Park Orthodontics","patient_portal_url"=>"http://rewards.ashbyparkortho.com"],
          ["clinic"=>"Pediatric Dentistry of Matthews","patient_portal_url"=>"http://rewards.pediatricdentistryofmatthews.com"],
          ["clinic"=>"Benedetti Orthodontics","patient_portal_url"=>"http://rewards.fortlauderdaleortho.com"],
          ["clinic"=>"Steele Creek Pediatric Dentistry","patient_portal_url"=>"http://rewards.steelecreekpediatricdentistry.com/"],
          ["clinic"=>"Fusion Children's Dentistry & Orthodontics","patient_portal_url"=>"http://rewards.fusiondentalclinic.com"],
          ["clinic"=>"West Valley Pediatric Dentistry & Orthodontics","patient_portal_url"=>"http://rewards.wvpd.com/"],
          ["clinic"=>"Webb Orthodontics","patient_portal_url"=>"http://rewards.webb-orthodontics.com"],
          ["clinic"=>"Forest Park Orthodontics","patient_portal_url"=>"http://rewards.forestparkorthodontics.com"],
          ["clinic"=>"OX Orthodontix","patient_portal_url"=>"http://rewards.oxorthodontix.com"],
          ["clinic"=>"Ashby Park Pediatric Dentistry","patient_portal_url"=>"http://rewards.wild4smiles.com"],
          ["clinic"=>"Smiles Orthodontics","patient_portal_url"=>"http://rewards.mysmilesortho.com"],
          ["clinic"=>"Connolly Orthodontics","patient_portal_url"=>"http://rewards.connollyorthodontics.com"],
          ["clinic"=>"Carolina Facial Plastics","patient_portal_url"=>"http://kulbizzy.integratelive.sourcefuse.com"],
          ["clinic"=>"My Clinic","patient_portal_url"=>"http://myclinic.integratelive.sourcefuse.com"],
          ["clinic"=>"East Shore Smile Solutions","patient_portal_url"=>"http://mydemoclinic.integratelive.sourcefuse.com"],
          ["clinic"=>"Dentistry for Children and Teens","patient_portal_url"=>"http://rewards.childteen.com"],
            ["clinic"=>"Skin Center","patient_portal_url"=>"http://rewards.skincenter.com"],
            ["clinic"=>"Cusimano Orthodontics","patient_portal_url"=>"http://rewards.cusimanoorthodontics.com"],
            ["clinic"=>"Better For Life","patient_portal_url"=>"http://better4life.buzzydoc.com"]
            ];

            $vendors = $this->Vendors->find()->where(['id >' => 4])->toArray();

            foreach ($vendors as $key => $value) {
              $data = array();
              $data['clinic']=$value['org_name'];
              $data['patient_portal_url']= "https://app.buzzydoc.com/patient-portal/".$value['id'].'#/';
              $buzzyVendorList[] = $data;
            }
            $this->set(compact('buzzyVendorList'));
            $this->set('_serialize', ['buzzyVendorList']);
          }

        }
