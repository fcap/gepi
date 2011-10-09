/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import java.awt.Color;
import  javax.swing.table.DefaultTableCellRenderer   ; import  java.awt.Component    ; import java.util.Vector   ;
import  javax.swing.JTable    ;  import java.util.Hashtable     ;import javax.swing.JLabel     ;
import java.awt.Dimension   ;  import  javax.swing.table.TableCellRenderer    ;
import java.awt.Graphics    ;        /**
 *
 * @author root
 */
// extends JLabel    implements   TableCellRendere
public class CEPMonthCellRenderer  extends DefaultTableCellRenderer    {
     public Hashtable  indexH   = new Hashtable(   )   ; public boolean isAbs   =  false     ;
public Vector   monthL      ;


   public  CEPMonthCellRenderer(  )  {
/* monthS    = new Hashtable(    )    ;
 monthS.put(    new Int, ui)
 * */
 
   }

 public Component   getTableCellRendererComponent(JTable table, Object value, boolean isSelected, boolean hasFocus, int row, int column)  {
JLabel jlab  =  new  JLabel(  )   ;  jlab.setOpaque(    true    )   ;
  if(  isSelected  )  {     jlab.setBackground(Color.green);    }
 if(  column == 0 )     {

  boolean  ok   = setMonthName(      row    ,    jlab     )      ;
     if(  ok  )    { return  jlab    ;}


  }
//un  jour de   vacances
if(  isHolyday(  column   ,    row     )    )  {   jlab.setBackground(Color.white);  return  jlab    ;      }
//fin vacances
//1  samedi   ou dimanche
//System.out.println ( "render row :"+row+" column:"  +column )    ;
if(   isSunday(  column  , row   ) )  {
jlab.setBackground(Color.white);  jlab.setText(  "D")  ; return  jlab    ;
}
if(   isSaturday(  column   , row    ) )  {
 jlab.setBackground(Color.white);    jlab.setText(  "S")  ;return  jlab    ;
}

// fin samedi  
     Object  obj      =  indexH.get(    new  Integer(   row  )    )  ;System.out.println ( " render   month  row:"+row+" "+obj    )    ;
if(   obj  !=null     )  {
     Vector tab   =(Vector)obj   ; int  [ ] tabI  ;
  for(  int   p = 0  ;p<tab.size( )   ;p++)  {
   tabI    =(int[ ])tab.elementAt(   p )   ;  System.out.println ( " render   month  :"+tabI   )    ;
for(   int   h  =  tabI[0]   ;h<tabI[1]+1;h++)   {   
   if(     h  == column)    {
     System.out.println ( " render   rowx  :"+row+"  column:"+column    )    ;
jlab.setText ("abs" ); jlab.setForeground( Color.red   )  ;
  if(  isSelected  )  {
    jlab.setBackground(Color.green);
  }else  {
     jlab.setBackground(Color.blue);
  }
  jlab.repaint()    ;
 

   }else    {  //pas  ds une  colonne abs

   }

  
 

   
}
  }


    
}  //fin  obj  !=null
   
     
   if(  isSelected  )  {
    jlab.setBackground(Color.green);
  }
     return   jlab   ;
   }

    


 public  boolean    setMonthName(  int  row    , JLabel  jlab     )  {  //on  affcete nom du mois   à la rangee  corresponbdante
    if( row == 0 )   { jlab.setText( "Aout")   ;  return true  ;  }
     if( row == 1 )   { jlab.setText( "Sepetmbre")   ; return true  ;}
         if( row == 2 )   { jlab.setText( "Octobre")   ;return true  ;}
          if( row == 3 )   { jlab.setText( "Novembre")   ;return true  ;}
          if( row == 4 )   { jlab.setText( "Decembre")   ;return true  ;}
       if( row == 5 )   { jlab.setText( "Janvier")   ;}
  if( row == 6 )   { jlab.setText( "Fevrier")   ; return true  ; }
   if( row == 7 )   { jlab.setText( "Mars")   ;return true  ;}
    if( row == 8 )   { jlab.setText( "Avril")   ;return true  ;}
      if( row == 9 )   { jlab.setText( "Mai")   ;return true  ;}
       if( row == 10 )   { jlab.setText( "Juin")   ;return true  ; }
    return false    ;
 }

 public void paint( Graphics g )
	{
		Color		bColor;

		// Set the correct background colour
		if( isAbs )
			bColor = Color.red  ;
		else
			bColor = Color.white;
		
		g.setColor( bColor );

		// Draw a rectangle in the background of the cell
		g.fillRect( 0, 0, getWidth() - 1, getHeight() - 1 );

		super.paint( g );
	}

 public boolean  isHolyday( int  column   , int row     )  {
   Month mth     ;
     for ( int  s   = 0  ;  s<monthL.size()    ;s++ )   {
   mth  =(Month)monthL.elementAt(   s   )   ;
   if(    mth.indexposition  == row    )   {
 if(   mth.dayoff.contains(  new Integer( column )) )   { return true     ;}
   }
    }
   return false    ;
 }

  public boolean isSunday( int  column   , int row  )  {
    Month mth    ;
     for ( int  s   = 0  ;  s<monthL.size()    ;s++ )   {
   mth  =(Month)monthL.elementAt(   s   )   ;
   if(    mth.indexposition  == row    )   {
  //  System.out.println (  "month indexposition :"+mth.indexposition+" sundayL size:"+mth.sundayL.size() +" column:"+column +" sundayL:"+mth.sundayL  )   ;
       if(   mth.sundayL.contains(  new Integer( column )) )   { return true     ;}
   }
    }
   return false    ;

  }

   public boolean isSaturday( int  column   , int row  )  {
    Month mth    ;
     for ( int  s   = 0  ;  s<monthL.size()    ;s++ )   {
   mth  =(Month)monthL.elementAt(   s   )   ;
   if(    mth.indexposition  == row    )   {
 if(   mth.saturdayL.contains(  new Integer( column )) )   { return true     ;}
   }
    }
   return false    ;

  }


}
