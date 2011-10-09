/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import java.awt.Color;
import  javax.swing.table.DefaultTableCellRenderer   ; import  java.awt.Component    ;import  java. util.Vector   ;
        import  javax.swing.JTable    ;  import java.util.Hashtable     ;import javax.swing.JLabel     ;
/***
 *
 * @author root
 */
public class CEPCellRenderer extends DefaultTableCellRenderer {
     public Hashtable  indexH   = new Hashtable(   )   ;  public  Vector    indexL    =  new Vector( )    ; public boolean fullrow   = false    ; //si  ttes les rangées  pour les colonnes ds  indexL  rempolies  


 public Component   getTableCellRendererComponent(JTable table, Object value, boolean isSelected, boolean hasFocus, int row, int column)  {
//Component cp  = 
JLabel jlab  =  new  JLabel(  )   ;
 jlab.setOpaque(    true    )   ;
  if(  isSelected  )  {     jlab.setBackground(Color.green);    }
if(  !fullrow)   {
    
         Object obj    =  indexH.get(    new  Integer(   row  )    )  ; //tab  de borne d'index
if(   obj  !=null     )  {
   Integer   it   =  ( Integer)obj    ;
      if(  it.intValue( )  ==  column  )  {

           if(  isSelected  )  {
    jlab.setBackground(Color.green);
  }else  {
     jlab.setBackground(Color.blue);
  }


   jlab.setText ("abs" );  jlab.repaint( )   ;return jlab   ;

 }

}else  {

  } //fin else 
    
}else   {  //fullrow 
 if( indexL.contains(    new Integer(  column)) )    {  
    if(  isSelected  )  {
    jlab.setBackground(Color.green);
  }else  {
     jlab.setBackground(Color.blue);
  }


   jlab.setText ("abs" );  jlab.repaint( )   ;return jlab   ;    
 }   
    
     
}
     

        if(  column == 0 )     {
    
    setCreneauName(    row    ,    jlab    )     ;
 
   }
                jlab.repaint()    ;
  return   jlab   ;

 }

      public  void setCreneauName(   int  row    , JLabel  jlab    )  {
   if( row == 0 )   { jlab.setText( "M1")   ;}
     if( row == 1 )   { jlab.setText( "M2")   ;}
         if( row == 2 )   { jlab.setText( "M3")   ;}
          if( row == 3 )   { jlab.setText( "M4")   ;}
          if( row == 4 )   { jlab.setText( "M5")   ;}
       if( row == 5 )   { jlab.setText( "S1")   ;}
  if( row == 6 )   { jlab.setText( "S2")   ;  }
     if( row == 7 )   { jlab.setText( "S3")   ;}
    if( row == 8 )   { jlab.setText( "S4")   ;}

      }
      




}
