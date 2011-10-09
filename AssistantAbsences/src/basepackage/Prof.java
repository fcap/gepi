/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;

/**
 *
 * @author root
 */
public class Prof {
  public String nom ,prenom   , discipline  ,civilite  , birthS    ,ident   , password  ;


  public boolean equals ( Object o  ) {
        try  {
     Prof   prf   = (Prof)o  ;
          if( prf.nom    .equals ( this.nom     )  &&  prf.prenom    .equals ( this.prenom     )  ) {  return true  ;}
        } catch  (ClassCastException ep   ) { return false  ;}
      return false  ;
    }
   public  int hashCode()  {  return  45  ;}

    public int  compareTo (   Object  o ) {//classement par ordre alphabetique
 try  {
        Prof  elv  = (Prof)o  ;
       return this.nom.compareTo ( elv.nom   ) ;//defaut
 } catch ( ClassCastException ep   ) {  return -1 ;   }
   }






}
