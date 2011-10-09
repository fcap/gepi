/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * CPEApplet.java
 *
 * Created on Oct 2, 2011, 10:38:48 AM
 */
package basepackage;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;  import    java.net.* ;
import javax.swing.Timer;import java.util.Vector   ;import java.util.Calendar   ;
import javax.swing.Icon; import java.util.GregorianCalendar  ;  import java. io.*    ;   import  java.util.StringTokenizer;
        import javax.swing.JDialog;  import javax.swing.DefaultComboBoxModel     ;import java.util.Date   ;
import javax.swing.JFrame; import  javax.swing.JOptionPane   ;  import  java.util.Calendar    ;

/**
 *
 * @author root
 */
public class AssistantAbsences extends javax.swing.JApplet {
  public JFrame  parentF   ;
public BaseInspector   bpe   ;public ListRendering  rendererL   ;public int userid =-1  ;
 public  CPEUtility  cpu   ;public GestionAbsences gbs  ;  //public Hashtable weekendH    ;
 public DefaultComboBoxModel classemodel     ; public Vector  monthL   , holydayL   , weekL  ,motifL     ;
 public int [ ] anneeI    ; //2011-2012 
 public String ident  , passwd  , dbname     ; //identifiants  
    /** Initializes the applet CPEApplet */
    public void init() {
        try {
            java.awt.EventQueue.invokeAndWait(new Runnable() {

                public void run() {
                   
                  if(    !testConnection(    )   )  {  //entez des  donnes db   à la main  
             
                  }
                
                    initComponents();initAll(  )    ;
                showGestionAbsences(  )     ;     
         
                    
                }
            });
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }
    
      
        
  
    
    
     public boolean    testConnection(    )   {
    bpe  = new  BaseInspector(  )   ; 
          Vector result  = new Vector( )   ;
        try {
 String path  =   this.getCodeBase( )  +"connect.inc.txt"  ; //System.out.println ( path   )   ;

            URL  ur = new URL ( path    );
  URLConnection   urt = ur.openConnection ();
 urt.setDoInput ( true ) ;

 BufferedReader   brd  = new BufferedReader ( new InputStreamReader(ur.openStream() ));
 
            String line  ; int  index1  ,  index2    ;
            line     = brd.readLine()     ; 
             
            while ( (line=brd.readLine())!= null ) {
                System.out.println (  "line       :"+line  )    ; 
           index1  = line.indexOf("$dbDb"  ,  0  )      ;
           if( index1 !=-1  )    { 
                            index2  = line.indexOf("="  ,  index1+1   )      ;
           dbname     =  returnChamp( line.substring(  index2+1  ,   line.length()   )     )  ; 
           }
            index1  = line.indexOf("$dbUser"  ,  0  )      ;
           if( index1 !=-1  )    { 
                            index2  = line.indexOf("="  ,  index1+1   )      ;
          ident      =  returnChamp( line.substring(  index2+1  ,   line.length()   )     )  ; 
           }
           
                        index1  = line.indexOf("$dbPass"  ,  0  )      ;
           if( index1 !=-1  )    { 
                            index2  = line.indexOf("="  ,  index1+1   )      ;
          passwd      =       returnChamp( line.substring(  index2+1  ,   line.length()   )     )  ; 

           }
                            
                
            }
            brd.close( ) ;
            
     if(    bpe.testConnection(ident, passwd, dbname, 3306  )    )   {
      bpe.ident   = ident  ;   bpe.passwd   = passwd   ; bpe.dbname   = dbname    ;      
              return true    ;  
     }
        }catch (IOException e ) {System.out.println(e ) ; return false     ; }
      return false  ; 
     }
     
       public String  returnChamp(  String  val )     {
           int index1      = val.indexOf("\"")   ; int index2      = val.indexOf("\""  , index1+1 )   ; 
           return  val.substring(   index1 +1  , index2  )    ; 
       }
    
     public void initAll(  ) {
     anneeI   =  new int[2]   ; anneeI[0]  = 2011   ;   anneeI[1]  = 2012   ;
   rendererL   = new ListRendering(  )    ;
parentF  = new JFrame(  )   ;cpu  = new CPEUtility(  )    ;weekL    = new Vector(  )    ;   bpe.cpu   = cpu    ; 
classemodel  =  new DefaultComboBoxModel(  )   ; motifL    = new  Vector(     )   ;
monthL = new Vector(   )    ;Month  mth    ;GregorianCalendar  gcd    = new GregorianCalendar(  )  ;  //indexpos   :  1   =   lihne d'headers
mth  = new Month(  ) ;mth.position =  1  ; mth.indexposition = 5  ; mth.name  ="janvier"    ; mth.abrev  ="jan"   ; mth.nbdays   = 31   ;  mth.yearS =""+anneeI[1]       ;  monthL.add( mth  )    ;
mth  = new Month(  )   ;  mth.name  ="fevrier" ;mth.position =  2 ; mth.indexposition = 6 ; mth.abrev  ="fev"   ; mth.nbdays   = 28   ;mth.yearS =""+anneeI[1]    ;monthL.add( mth  )    ;
mth  = new Month(  )   ;  mth.name  ="mars"    ; mth.abrev  ="mar" ;mth.position = 3;  mth.indexposition = 7 ; mth.nbdays   = 31   ;mth.yearS =""+anneeI[1]     ;monthL.add( mth  )    ;
mth  = new Month(  )   ;  mth.name  ="avril"    ; mth.abrev  ="avr"   ; mth.nbdays   = 30   ;mth.position =4 ;  mth.indexposition =  8  ;mth.yearS =""+anneeI[1]     ;monthL.add( mth  )    ;
//setSundayForAvril(  mth  );
mth  = new Month(  )   ;  mth.name  ="mai"    ; mth.abrev  ="mai"   ; mth.nbdays   = 30   ;mth.position =  5 ; mth.indexposition = 9  ;mth.yearS =""+anneeI[1]     ;monthL.add( mth  )    ;
//setSundayForMai(  mth  );
mth  = new Month(  )   ;  mth.name  ="juin"    ; mth.abrev  ="juin"   ; mth.nbdays   = 30   ;mth.position =  6 ; mth.indexposition =  10  ;mth.yearS =""+anneeI[1]     ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;
mth  = new Month(  )   ;  mth.name  ="juillet"    ; mth.abrev  ="juillet"    ; mth.nbdays   = 30   ;mth.position =  7 ; mth.indexposition =  11  ;mth.yearS =""+anneeI[1]     ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;
mth  = new Month(  )   ;  mth.name  ="aout"    ; mth.abrev  ="aout"    ; mth.nbdays   = 30   ;mth.position =  8 ; mth.indexposition =  0  ;mth.yearS =""+anneeI[0]     ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;
mth  = new Month(  )   ;  mth.name  ="septembre"    ; mth.abrev  ="septembre"    ; mth.nbdays   = 30   ;mth.position =  9 ; mth.indexposition =  1   ;mth.yearS =""+anneeI[0]    ;monthL.add( mth  )    ;
setMonthWeekend(   mth    ,   2011        )    ;

mth  = new Month(  )   ;  mth.name  ="Octobre"    ; mth.abrev  ="Octobre"    ; mth.nbdays   = 30   ;mth.position =  10 ; mth.indexposition =  2   ;mth.yearS =""+anneeI[0]    ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;

mth  = new Month(  )   ;  mth.name  ="Novembre"    ; mth.abrev  ="Novembre"    ; mth.nbdays   = 30   ;mth.position =  11 ; mth.indexposition =  3   ;mth.yearS =""+anneeI[0]    ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;

mth  = new Month(  )   ;  mth.name  ="Decembre"    ; mth.abrev  ="Decembre"    ; mth.nbdays   = 30   ;mth.position =  12 ; mth.indexposition =  4   ;mth.yearS =""+anneeI[0]   ;monthL.add( mth  )    ;
//setSundayForJuin(    mth  )   ;
bpe. readMotifAbsence (motifL     )     ;
  Vector tab   = bpe.getClasseForCPE(    userid   )      ;
if(   tab  .size()  >  0  )   {  cpu.addList(  tab  , classemodel  , ConstantFolder.classe   )    ; }
setHolyday(  )   ;setWeekList(  ) ;
 }
     
     public void setWeekList(  ) {
     GregorianCalendar  gcd    = new GregorianCalendar(  )  ;Week  wk   ;
      gcd.set (Calendar.MONTH   ,     7     )   ;  gcd.set (Calendar.DATE   ,   19   )     ; gcd.set (Calendar.YEAR   ,  2011   )   ;//derpart le  26 aout
    GregorianCalendar  gcd1    = new GregorianCalendar(  )  ;
  gcd1.set (Calendar.MONTH   ,     6     )   ;  gcd1.set (Calendar.DATE   ,   2   )     ; gcd1.set (Calendar.YEAR   ,  2012   )   ; // fin  le  03/07

  boolean  ok   =   true     ;Date dt   ;
      while ( ok  )  {
          wk = new Week(    )    ;


          gcd.set(Calendar.DAY_OF_WEEK   ,  7   )    ; 
    gcd.set(Calendar.HOUR_OF_DAY,   17  );   gcd.set(Calendar.MINUTE    , 0     );gcd.set(Calendar.SECOND     ,  0     );   wk.endD =    gcd.getTime(  )    ;   
    gcd.set(Calendar.DAY_OF_WEEK   ,  2   )    ;  //lundi  , vendredi
    gcd.set(Calendar.HOUR_OF_DAY,   7  );   gcd.set(Calendar.MINUTE    , 30    );gcd.set(Calendar.SECOND     ,  0     ); wk.startD =    gcd.getTime(  )    ; 
    System.out.println (    "setWeekList :"+wk.endD   )   ;
    
    wk.startDS   =  cpu.getMysqlDate( wk.startD )  ;  wk.endDS    =  cpu.getMysqlDate( wk.endD    )  ;
    gcd.add(Calendar.WEEK_OF_YEAR   , 1   )   ;weekL.add( wk  )  ;//Calendar.WEEK_OF_MONTH  WEEK_OF_YEAR
    
    
dt    =  gcd.getTime()   ;
if(  dt.after(  gcd1.getTime( )    )  )  { ok  =  false     ;}
       //si pas depassé date de  sortie


      }
    //  int pos     =    gcd.get(Calendar.MONDAY      )    ;
    System.out.println (  "monday :" +gcd.getTime(   )    )      ;  
     System.out.println (  "friday:" +gcd.getTime(   )    )  ;
 }

     
      public  void  setHolyday(  )   {
holydayL   = bpe.getDayOff(    )        ; System.out.println ( "holydayL :"+holydayL.size( )   ) ;//connection base gepi
GregorianCalendar  gcd   = new  GregorianCalendar(  )    ;Month  mth  ;
for ( int s  = 0  ; s<monthL.size()   ;s++)  {
  mth   =(Month)monthL.elementAt( s )   ;System.out.println ( "month :"+mth.name    ) ;
  setMonthHolyday (   mth   ,  gcd  )   ;System.out.println ( "month :"+mth.name    ) ;
//  setMonthWeekend(  mth    ,    gcd )  ;
}

  }
      
      public void setMonthWeekend( Month  mth , int year      ) {
   GregorianCalendar  gcd    = new GregorianCalendar(  )  ;  int position     =   mth.position  -1       ; 
    gcd.set (Calendar.MONTH   ,   mth.position  -1     )   ;      gcd.set (Calendar.DAY_OF_MONTH  ,1    )     ;    gcd.set (Calendar.YEAR   ,   year       )   ;
    boolean  ok   =  true       ;int compteur  =  1     ;
    while(  position  !=    mth.position  -1   )    {  //on est soryti du mois  
     int pos  =  gcd.get(Calendar.SUNDAY   )        ; System.out.println(  "sunday  :"+pos )    ;mth.sundayL.add(    new Integer(    pos  ))    ; 
     gcd.add( Calendar.WEEK_OF_MONTH , 1 )     ;
     position      =   gcd.get(   Calendar.MONTH   )  ;System.out.println(  "mois position :"+pos )    ;
    }
      //System.out.println( "Week :"+   gcd.getTime()   )     ;
  }
      
       public  void  setMonthHolyday( Month  mth    ,   GregorianCalendar  gcd      )   {
   Holyday  hds   ; GregorianCalendar  gcd1   = new GregorianCalendar(  )   ;
         for (int  p = 0  ;p<holydayL.size( ) ;p++ )    {
             hds     =  (Holyday)holydayL.elementAt( p )  ;System.out.println(  "holyday stardD:"+hds.startD +"  endD:"+hds.endD  )  ;
 gcd.setTime(   hds.startD    )  ;  gcd1.setTime(    hds.endD )    ;
             if(    mth.position >= gcd.get(Calendar.MONTH  ) +1  ||  mth.position <= gcd1.get(Calendar.MONTH  ) +1 )    {
      //il y a des jours  off ds ce mois
      gcd.setTime(  hds.startD   )    ;  boolean ok   =  true   ;  int mthpos   ;
      while (  ok  )    {
          mthpos  =  gcd.get(Calendar.MONTH  )  + 1    ;
 if(   mthpos  ==  mth.position )  {

     mth.dayoff.add(  new Integer(     gcd.get(Calendar.DAY_OF_MONTH  ) -1   )    )    ;System.out.println(  "add holyday :"+gcd.getTime( ) )  ;// 1  est dimanche 
 }

    if(      gcd.get(Calendar.MONTH  ) +1 > mth.position     ||  cpu.isDateSame(  gcd.getTime( ) ,hds.endD    ) )  { ok  = false   ;}
  gcd.add(Calendar.DATE, 1 )    ; 
          //test  sur le  isdate same   ou si on depasse opas le mois




      }
  }

    }  //fin fu for 
  }


    
    
             public  void showGestionAbsences(  )  {
  gbs =  new GestionAbsences(   )   ;gbs.motifL = motifL    ;gbs.weekL   =weekL    ;gbs.rendererL   = rendererL   ;gbs.cpu   = cpu  ;  gbs.bpe  = bpe  ;gbs.classemodel   =classemodel   ;
  gbs.monthL  = monthL    ;gbs.bpe   =bpe  ;gbs.init(   )   ;gbs.anneeI = anneeI     ;
  jTabbedPane1.add( gbs ,"Gestion Absences"  )   ;
  }   

    /** This method is called from within the init() method to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jTabbedPane1 = new javax.swing.JTabbedPane();
        jMenuBar1 = new javax.swing.JMenuBar();
        jMenu1 = new javax.swing.JMenu();
        jMenu2 = new javax.swing.JMenu();

        jTabbedPane1.setMinimumSize(new java.awt.Dimension(400, 500));
        jTabbedPane1.setPreferredSize(new java.awt.Dimension(400, 500));

        jMenu1.setText("File");
        jMenuBar1.add(jMenu1);

        jMenu2.setText("Edit");
        jMenuBar1.add(jMenu2);

        setJMenuBar(jMenuBar1);

        org.jdesktop.layout.GroupLayout layout = new org.jdesktop.layout.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
            .add(layout.createSequentialGroup()
                .addContainerGap()
                .add(jTabbedPane1, org.jdesktop.layout.GroupLayout.DEFAULT_SIZE, 603, Short.MAX_VALUE)
                .addContainerGap())
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
            .add(layout.createSequentialGroup()
                .add(28, 28, 28)
                .add(jTabbedPane1, org.jdesktop.layout.GroupLayout.PREFERRED_SIZE, org.jdesktop.layout.GroupLayout.DEFAULT_SIZE, org.jdesktop.layout.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(13, Short.MAX_VALUE))
        );
    }// </editor-fold>//GEN-END:initComponents
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JMenu jMenu1;
    private javax.swing.JMenu jMenu2;
    private javax.swing.JMenuBar jMenuBar1;
    private javax.swing.JTabbedPane jTabbedPane1;
    // End of variables declaration//GEN-END:variables
}
