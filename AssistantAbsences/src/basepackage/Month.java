/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import  java.util.Vector  ; import  java.util.Date     ;
/**
 *
 * @author root
 */
public class Month {
  public String name , abrev   ;  public int nbdays   , position  , indexposition   ;  //position   = 1 , 2   ,  3
public Vector  dayoff   , sundayL    , saturdayL      ;
   public Date   startD    , endD    ;
   public String  startDS  , endDS    , yearS    ;

 public   Month(  )   {
 dayoff =  new Vector(  )   ;  sundayL  = new Vector(  )  ; saturdayL  = new Vector(  )  ;
 }
}
