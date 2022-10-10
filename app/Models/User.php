<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='users';
    protected $table_codfis='users_codfis';
    protected $table_tmpverifiedmail='users_tmp_verifiedmail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
 
  
    /**
     * Metodo che ricerca utenti con l'indirizzo email passato nei parametri
     *
     *  @param string  $email email da cercare nella tabella
     * @return array
     *  
     */
    public function getUserFromEmail($email){
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*')
                ->where('u.email',$email);
    
        return $queryBuilder->get();
    }
    
    /**
     * Metodo che ricerca utenti con il codice fiscale passato nei parametri
     *
     *  @param string  $codfis codice fiscale da cercare nella tabella
     *  @return array
     *  
     */
    public function getUserFromCodfis($codfis){
        $queryBuilder=DB::table($this->table.' AS u')->select('u.*')
                 ->leftJoin($this->table_codfis.' AS ucf','ucf.uid','u.id')
                ->where('ucf.codfis',$codfis);
    
        return $queryBuilder->get();
    }
     
    
    public function setTMPVerifiedDate($id){
        $queryBuilder=DB::table($this->table_tmpverifiedmail)->insert(array('uid' => $id, 'startverified' => DB::raw('CURRENT_TIMESTAMP')));
        return true;
    }
    
    public function setCodiceFiscaleUtente($id,$codfis){
      $queryBuilder=DB::table($this->table_codfis)->insert(array('uid' => $id, 'codfis' => strtoupper($codfis)));
        return true;  
    }
    
    public function deleteTMPDateverified($id){
        $queryBuilder=DB::table($this->table_tmpverifiedmail)->where('uid', $id)->delete();
        return true;  
    }
    
}
