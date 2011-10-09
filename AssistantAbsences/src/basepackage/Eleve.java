/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import  java.util.Date   ;
/**
 *
 * @author root
 */
public class Eleve {
public  String  nom , prenom  , classe   , ine   , loginG      ;
public  Date  birthD      ;public int  id  ,idclasse    ;  public String dateS , sexe     ;


 public boolean equals ( Object o  ) {
        try  {
     Eleve  elv  = (Eleve)o  ;
          if( elv.nom    .equals ( this.nom     )  &&  elv.prenom    .equals ( this.prenom     )  ) {  return true  ;}
        } catch  (ClassCastException ep   ) { return false  ;}
      return false  ;
    }
   public  int hashCode()  {  return  45  ;}

    public int  compareTo (   Object  o ) {//classement par ordre alphabetique
 try  {
      Eleve  elv  = (Eleve)o  ;
       return this.nom.compareTo ( elv.nom   ) ;//defaut
 } catch ( ClassCastException ep   ) {  return -1 ;   }
   }

}
