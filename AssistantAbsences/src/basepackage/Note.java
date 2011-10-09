/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import  java.util.Date    ;
/**
 *
 * @author root
 */
public class Note {
public  int   id    , eleveid   ; public String content   , title  ;
 public Date  creationD    ;

    public boolean equals(Object o) {
        try {
            Note  elv = (Note) o;
            if (elv.title.equals(this.title )  ) {
                return true;
            }
        } catch (ClassCastException ep) {
            return false;
        }
        return false;
    }

    public int hashCode() {
        return 45;
    }

    public int compareTo(Object o) {//classement par ordre alphabetique
        try {
            Note  elv = (Note) o;
            return this.creationD  .compareTo(elv.creationD  );//defaut
        } catch (ClassCastException ep) {
            return -1;
        }
    }






}
