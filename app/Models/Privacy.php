<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DB;

class Privacy extends Model
{
   
    use HasFactory;
    protected $table='meoh_privacypolicy';
    protected $primaryKey='ppid';
    protected $table_users='users';
    protected $table_privacy_accettazione='meoh_privacypolicy_accettazione';
    
  
    public function __construct()
    {
        
        
    }
    
    public function getPrivacyAttuale(){
        $queryBuilder=DB::table($this->table.' AS pp')->select('pp.*')->where('pp.attuale',1);
        return $queryBuilder->first();
    }
    
    public function setAccettazione($uid,$tipologia=0){
        $privacyattuale=$this->getPrivacyAttuale();
        $queryBuilder=DB::table($this->table_privacy_accettazione)->insert(array('ppid' => $privacyattuale->ppid, 'uid' => $uid,'data_accettazione_visione'=>DB::raw('CURRENT_TIMESTAMP'),'tipologia'=>$tipologia));
        return true;  
    }

    
     
     
    
   



}
