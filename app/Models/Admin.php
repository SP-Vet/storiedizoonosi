<?php

namespace App\Models;
 
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;
 
class Admin extends Authenticatable
{
    use Notifiable,HasApiTokens, HasFactory;
    protected $guard = "admin";
    protected $guarded = [];
    


    protected $table='admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role',
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
     protected $visible = [
        'role'
    ];
     
      /**
     * Metodo che ricerca i membri del gruppo di lavoro nel sistema
     *
     *  @param array $where le condizioni di where in query
     *  @param array $order le condizioni di ordinamento in query
     *  @return 
     */
    public function getAll($where=[],$order=[]){
        
        $queryBuilder=DB::table($this->table.' AS a')->select('a.*');
                
        if(!empty($where))
            $queryBuilder->where($where);
                
        //$queryBuilder->groupBy('sl.sid');
        if(!empty($order))
            foreach ($order AS $key2=>$ord)
                $queryBuilder->orderBy($key2,$ord);
   
        return $queryBuilder->get();
    }
    
    public function getAllAttributes(){
        $columns = $this->getFillable();
        // Another option is to get all columns for the table like so:
        // $columns = \Schema::getColumnListing($this->table);
        // but it's safer to just get the fillable fields

        $attributes = $this->getAttributes();

        foreach ($columns as $column)
        {
            if (!array_key_exists($column, $attributes))
            {
                $attributes[$column] = null;
            }
        }
        return $attributes;
    }
    
    
 
}
