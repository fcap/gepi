/*
 * ListRendering.java
 *
 * Created on 28 octobre 2000, 11:27
 */

package basepackage;
import javax.swing.* ;import   java.awt.Component  ;import  java.util.Date   ;
import  java.util.GregorianCalendar   ;import  java.util.Calendar  ;
/**
 *
 * @author  Caparin Frederic
 * @version
 */
public class ListRendering extends DefaultListCellRenderer  {
   
    /** Creates new ListRendering */
    public ListRendering() {
    }
    public Component getListCellRendererComponent(JList list, Object value,  int index, boolean isSelected,boolean cellHasFocus) {
        ListObj lbj  =( ListObj)value  ;   String todisplay   = null ;
        if (  lbj  !=null     ) {


              if (  lbj.type  == ConstantFolder.motifabsence     ) {   //eleve
       AbsenceMotif   abm     =( AbsenceMotif )lbj.value     ;
       return super.getListCellRendererComponent( list , abm.motif           , index  , isSelected , cellHasFocus  ) ;
       }

              if (  lbj.type  == ConstantFolder.week    ) {   //eleve
       Week  wk    =(Week)lbj.value     ;
       todisplay = " Du "+wk.startDS+" à "+wk.endDS   ;
       return super.getListCellRendererComponent( list , todisplay          , index  , isSelected , cellHasFocus  ) ;
       }

            if (  lbj.type  == ConstantFolder.month    ) {   //eleve
       Month  mth    =(Month)lbj.value     ;
          return super.getListCellRendererComponent( list , mth.name       , index  , isSelected , cellHasFocus  ) ;
       }

       if (  lbj.type  == ConstantFolder.eleve    ) {   //eleve
       Eleve   elv   =(Eleve)lbj.value     ;
          return super.getListCellRendererComponent( list , elv.nom+" "+elv.prenom   , index  , isSelected , cellHasFocus  ) ;
       }

         if (  lbj.type  == ConstantFolder.user     ) {   //eleve
      User   us    =(User)lbj.value     ;
          return super.getListCellRendererComponent( list , us.nom+" "+us.prenom   , index  , isSelected , cellHasFocus  ) ;
       }
            
          
      if (  lbj.type  == ConstantFolder.note    ) {  //pour leportfefuille
            Note   dtr          =( Note  )lbj.value  ;
           String text   =   dtr.title+" "+getMysqlDate(  dtr.creationD  )  ;
          return super.getListCellRendererComponent( list , text      , index  , isSelected , cellHasFocus  ) ;
            }               
            
     if (  lbj.type  == ConstantFolder.classe     ) {  //pour leportfefuille
            Classe   cls        =( Classe  )lbj.value  ;
                   return super.getListCellRendererComponent( list , cls.nom       , index  , isSelected , cellHasFocus  ) ;
            }
        
       
        
    }
         return super.getListCellRendererComponent( list , "Pb"        , index  , isSelected , cellHasFocus  ) ;
        }
    
    public   String  getMysqlDate( Date dt  ) {  //donne lengthstring qui correspond pour  mysql
        String val  =""  ;
        GregorianCalendar gcd  =   new  GregorianCalendar  (  ) ;gcd.setTime( dt  );
        int dy    = gcd.get(Calendar.DAY_OF_MONTH   )  ;
        String dyS  =""  ;String  myS=""  ;
        if( dy < 10 )  {  dyS =0+""+dy  ;} else  {  dyS =""+dy  ;}
        int my  = dt.getMonth( )+1 ;
        if( my < 10 )  {  myS ="0"+my  ;} else {  myS =""+my  ;  }
        int yd  = dt.getYear(  )+1900 ;
        val =""+yd+"-"+myS+"-"+dyS  ;
        return val  ;

    }
    
}