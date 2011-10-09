/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
 import   java.io.File  ;import  javax.swing.JFileChooser  ;
import  javax.swing.JFrame   ;import  javax.swing.DefaultComboBoxModel   ;
import  java.util.Vector   ;  import java.io.File   ; import java.io.FileReader  ;
import  java.io.BufferedReader   ;  import java.io.IOException   ; import java.util.Date    ;
;import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import  java.util.GregorianCalendar    ;import  java.util.Calendar  ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import  java.util.GregorianCalendar    ;import  java.util.Calendar  ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import  java.util.GregorianCalendar    ;import  java.util.Calendar  ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import  java.util.GregorianCalendar    ;import  java.util.Calendar  ;
/*
 *
 * @author root
 */
public class CPEUtility {
   GregorianCalendar gcd  , gcd1      ;
 public CPEUtility(  )   {
    gcd  =   new  GregorianCalendar  (  )   ;     gcd1  =   new  GregorianCalendar  (  )   ;
 }

 public Month  getMonth( int  position    , Vector  tab     )   {
     Month mth    ;
     for( int s   =  0  ;  s<tab.size()    ;s++)  {
  mth  =  (Month)tab.elementAt( s  )    ;
  if(  mth .position  ==  position  )   { return  mth    ;}
      }
     return null   ;
 }

    public   void addList( Vector tab  , DefaultComboBoxModel dlm , int eltype   )  {
    dlm.removeAllElements();    ;
         for ( int  h = 0 ;h<tab.size(  ) ;h++ ) {
            ListObj  lbj   = new ListObj(  ) ;lbj.value  =tab.elementAt( h  ) ;  lbj.type  = eltype  ;
            dlm.addElement(  lbj   ) ;
        }
    }

 public   void addList( Vector tab  , DefaultListModel dlm , int eltype   )  {
    dlm.removeAllElements();    ;
         for ( int  h = 0 ;h<tab.size(  ) ;h++ ) {
            ListObj  lbj   = new ListObj(  ) ;lbj.value  = tab.elementAt( h  ) ;  lbj.type  = eltype  ;
            dlm.addElement( lbj   ) ;
        }
    }

  public  boolean isDateSame(  Date dt1   , Date dt2    )  {
  //System.out.println (  "dt1 :"+dt1+" dt2:"+dt2 )   ;
     gcd.setTime(  dt1 )  ;gcd1.setTime(  dt2 )   ;
  if(   gcd.get(Calendar.DAY_OF_MONTH)   ==  gcd1.get(Calendar.DAY_OF_MONTH)   &&   gcd.get(Calendar.YEAR )  == gcd1.get(Calendar.YEAR )   &&  gcd.get(Calendar.MONTH  )  == gcd1.get(Calendar.MONTH ) )   {
   return true   ;
  }
    // if(  dt1.getMonth()   ==  dt2.getMonth()   && dt1.getDay()  == dt2.getDay() && dt1.getYear()   ==dt2.getYear() )   { return true  ;}
 return false    ;
  }

  public   String  getMysqlDate( Date dt  ) {  //donne lengthstring qui correspond pour  mysql
        String val  =""  ;
       gcd.setTime( dt  );
        int dy    = gcd.get(Calendar.DAY_OF_MONTH   )  ;
        String dyS  =""  ;String  myS=""  ;
        if( dy < 10 )  {  dyS =0+""+dy  ;} else  {  dyS =""+dy  ;}
        int my  = dt.getMonth( )+1 ;
        if( my < 10 )  {  myS ="0"+my  ;} else {  myS =""+my  ;  }
        int yd  = dt.getYear(  )+1900 ;
        val =""+yd+"-"+myS+"-"+dyS  ;
        return val  ;

    }

   public Date findDateInWeek (Week  wk   ,  int daypos  )  {
gcd.setTime(  wk.startD  )    ;int compteur  = 1  ;
while  ( compteur < 6  )  {
    if(  gcd.get(Calendar.DAY_OF_WEEK   )   ==  daypos   )     {  return gcd.getTime(  )    ;   }
compteur++   ;    gcd.add(Calendar.DATE   , 1 )    ;
}
return null  ;

}

    public String  getActualDateTime(     )    {
 GregorianCalendar    gcd         = new GregorianCalendar(     )    ;
 return   getMysqlDateTime(   gcd.getTime()       )   ; 
    }
   

        public   String  getMysqlDateTime( java.util.Date     dt  ) {  //donne lengthstring qui correspond pour  mysql
        String val  =""  ;
        GregorianCalendar gcd  =   new  GregorianCalendar  (  ) ;gcd.setTime( dt  );
        int dy    = gcd.get(Calendar.DAY_OF_MONTH   )  ;
        String dyS  =""  ;String  myS=""  ;
        if( dy < 10 )  {  dyS =0+""+dy  ;} else  {  dyS =""+dy  ;}
        int my  = dt.getMonth( )+1 ;
        if( my < 10 )  {  myS ="0"+my  ;} else {  myS =""+my  ;  }
        int yd  = dt.getYear(  )+1900 ;
        val =""+yd+"-"+myS+"-"+dyS+" "  ;
   int  hh    =  dt.getHours()    ;
    if(   hh   < 10 )     {   val +="0"+hh   ;  }
   else  {val  +=hh ;  }
   val +=":"   ;
           int min    =       dt.getMinutes();
   if(   min  < 10 )     {    val +="0"+min   ; }
   else  {  val +=min   ; }
        val +=":"   ;
 int ss   = dt.getSeconds()    ;
 if(   ss  < 10 )     {    val +="0"+ss   ; }
   else  {  val +=ss   ; }
        return val  ;

    }

          public   String  getMysqlTime(  java.util.Date     dt   ) {  //formar    hh:mm
        String val  =""  ;
        GregorianCalendar gcd  =   new  GregorianCalendar  (  ) ;gcd.setTime( dt  );
        
        int  hh    =  gcd.get(  Calendar.HOUR_OF_DAY    )       ;  // dt.getHours()    ;
    if(   hh   < 10 )     {   val +="0"+hh   ;  }
   else  {val  +=hh ;  }
   val +=":"   ;
           int min    =  gcd.get(  Calendar.MINUTE     )   ; //     dt.getMinutes();
   if(   min  < 10 )     {    val +="0"+min   ; }
   else  {  val +=min   ; }
      
        return val  ;

    }
          
          
          public   String  getMysqlTime( java.sql.Time    dt  ) {  //donne lengthstring qui correspond pour  mysql
        String val  =""  ;
        GregorianCalendar gcd  =   new  GregorianCalendar  (  ) ;gcd.setTime( dt  );
        int  hh    =  dt.getHours()    ;
    if(   hh   < 10 )     {   val +="0"+hh   ;  }
   else  {val  +=hh ;  }
   val +=":"   ;
           int min    =       dt.getMinutes();
   if(   min  < 10 )     {    val +="0"+min   ; }
   else  {  val +=min   ; }
        val +=":"   ;
 int ss   = dt.getSeconds()    ;
 if(   ss  < 10 )     {    val +="0"+ss   ; }
   else  {  val +=ss   ; }
        return val  ;

    }






}