/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * GestionAbsences1.java
 *
 * Created on Oct 2, 2011, 3:12:16 PM
 */
package basepackage;
import java.awt.Color;
 import   java.io.File  ;import  javax.swing.JFileChooser  ;
import  javax.swing.JFrame   ;import  javax.swing.DefaultComboBoxModel   ;import  java.awt.Component    ;
import  java.util.Vector   ;  import java.io.File   ; import java.io.FileReader  ;
import  java.io.BufferedReader   ;  import java.io.IOException   ; import java.awt.Color    ;
;import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
import  java.io.FileWriter   ;import java.util.Hashtable   ;import  javax.swing.DefaultListModel ;
import java.io.PrintWriter    ;import javax.swing.JOptionPane   ;import java.util.Enumeration  ;
import java.util.GregorianCalendar   ;import java.util.Calendar   ; import  javax.swing.JToggleButton    ;
import javax.swing.JPopupMenu    ;import javax.swing.JMenuItem    ;import java.awt.event.ActionListener     ;
import  javax.swing.ListSelectionModel     ;  import java.awt.event.ActionEvent    ;import java.util.Date    ;
import  javax.swing. DefaultCellEditor   ;  import   javax.swing.JTextField  ;  import javax.swing.table.TableColumnModel    ;
import  javax.swing.table.DefaultTableCellRenderer   ;  import  javax.swing.table.TableColumn    ;import   javax. swing.JTable   ;
import  javax.swing.table.TableCellRenderer    ;
/**
 *
 * @author root
 */
public class GestionAbsences extends javax.swing.JPanel {
public  DefaultComboBoxModel classemodel   , semainemodel     ; public BaseInspector  bpe     ;public Vector weekL    , motifL     ;
public CPEUtility  cpu     ;  public ListRendering  rendererL    ;  public DefaultListModel listemodel   ;public SelectionMotif  smf   ;
public boolean initialised    = false      ; public Vector monthL    ;    public GregorianCalendar gcd  ; public int [ ] anneeI    ;
        public JPopupMenu jpm  , jmonth   ;public Vector   semaineLS   , tabE    ; public Hashtable tabMH  , tabMHS  , absH   , tabAH    ;  //tabAH : map position du mois avec index ds jtable
public Week  actualW     ;  public   CEPCellRenderer   cep       ;public CEPMonthCellRenderer    cmc     ;public JFrame  parentF    ;
    /** Creates new form GestionAbsences1 */
    public GestionAbsences() {
        initComponents();
    }
    
    
     public void init(  ) {
createAbsenceMenu(   )  ;tabMH   = new Hashtable(  )   ;tabMHS   = new Hashtable(  )   ;absH  = new Hashtable(   )    ;
/*tabMH.put ( new Integer( 0)   , "07:30:00"    )    ;tabMH.put ( new Integer( 1)   , "08:30:00"    );tabMH.put ( new Integer( 2)   , "09:30:00"    ) ;tabMH.put ( new Integer( 3)   , "10:30:00"    )    ;
tabMHS.put ( new Integer( 0)   , "08:30:00"    )    ;tabMHS.put ( new Integer( 1)   , "09:30:00"    );tabMHS.put ( new Integer( 2)   , "10:30:00"    ) ;tabMHS.put ( new Integer( 3)   , "11:30:00"    )    ;
 */
  bpe.initCreneauHoraire(    tabMH    ,     tabMHS  )   ;  
tabAH  = new Hashtable(   )    ;

jComboBox4.setModel(   classemodel  );  //classemodel est dejà  rempli au   logging
 jComboBox4.setRenderer(    rendererL  );
 listemodel  =  new DefaultListModel(  )  ;jList1.setModel(   listemodel );jList1.setCellRenderer(   rendererL  );
// panelMensuel1.setMonthList(    monthL     )   ; panelMensuel1.init(   )    ;
 gcd = new GregorianCalendar(  )   ;
 semainemodel   = new DefaultComboBoxModel(  )  ;jComboBox3.setModel(  semainemodel    )    ;
     jComboBox3.setRenderer(    rendererL  );semaineLS    = new Vector(   )   ; tabE    = new Vector(  )    ;
     cpu.addList(weekL , semainemodel  , ConstantFolder.week  )   ;setActualWeek(   )   ;
//jTable1.setCellEditor(new DefaultCellEditor(  new JTextField( )))    ;
  cep    = new CEPCellRenderer( )    ;cmc   =  new CEPMonthCellRenderer(    )    ;cmc. monthL    = monthL    ;
          jTable1.setDefaultRenderer( Object.class  ,  cep)     ;jTable2.setDefaultRenderer( Object.class  ,  cmc  )     ;
 //setTableColumns(  jTable2  ,  cmc     )    ;//
jTable2.setRowHeight(   60   )   ;
jTable2.getColumnModel().getColumn(   0 ).setWidth(   150  );
parentF    = new JFrame(   )    ;
jSplitPane1 .setDividerLocation(    0.8     )   ;            
          initialised    =   true    ;
     }
     
     
      public void setTableColumns(   JTable table  ,  TableCellRenderer tcr   )   {
 table.setAutoCreateColumnsFromModel( false );

          for( int  s  =1 ;s<33 ;s++)  {
              TableColumn column = new TableColumn( s  );
			column.setHeaderValue(   "titre" );

			// Add a cell renderer for this class
			column.setCellRenderer( tcr   );
table.addColumn( column  );

          }

 
      }
       //Attention   :  pb  si on consulte   l'app  1 samedi 

       public  void  setActualWeek(   )  {
  Date  dt  =     gcd.getTime(  )   ;Week  wk  ;
  for( int  s   =0 ;s<weekL.size()     ;s++ )  {
      wk =(Week)weekL.elementAt(  s   )    ;
System.out.println ( "dt:"+dt+" wk  startD"+wk.startD+" wk endD"+wk.endD )    ;
        if(  ( dt.after( wk.startD )    &&     dt.before( wk.endD  )  )    ||   ( cpu.isDateSame( dt, wk.startD)  ||   cpu.isDateSame( dt, wk.endD )     ) )  {
    actualW     =wk   ;
    jComboBox3.setSelectedIndex( s);
              }
  }
       }

      public void  createAbsenceMenu(   )  {
         jmonth    = new JPopupMenu(    )    ; JMenuItem  jtm ;
  jtm     =  new  JMenuItem(  "Ajouter Multiple Absence")    ;
  jtm.addActionListener(     new ActionListener(  )
 {
//colonne  1  =  lundi    , M1  = rang  0
    public void actionPerformed(  ActionEvent   evt)   {
semaineLS.removeAllElements()   ;tabE.removeAllElements()    ; //il  faut  +1  à  ts les index  car demarrage  à  0
ListSelectionModel lsm  =  jTable2.getSelectionModel(   )    ;
int rowIndexStart = jTable2.getSelectedRow();
int colIndexEnd = jTable2.getColumnModel().getSelectionModel() .getMaxSelectionIndex(); ;
int colIndexStart = jTable2.getColumnModel().getSelectionModel().getMinSelectionIndex();
//int colIndexStart = lsm.getMinSelectionIndex()    ;//tetMinSelectionIndex(); // Check each cell in the range
Absence abs   = new Absence(   )  ;
setMultipleDayAbsence(    abs     ,  rowIndexStart  , colIndexStart     , colIndexEnd   )  ;
System.out.println( "  rowIndexStart:"+ rowIndexStart+" colIndexEnd:"+colIndexEnd+" colIndexStart:"+colIndexStart +"  startD:"+abs.startD +"  endD:"+abs.endD  ) ;
Object  [ ]  tabO   = jList1.getSelectedValues()    ;ListObj lbj   ;
     for (     int  p = 0  ; p<tabO.length  ;p++)   {
            lbj   =   (ListObj)tabO[p]    ;
             tabE.add(   lbj.value     )      ;
     }
     System.out.println (    "tabE size :"+tabE.size()   )   ;
if(  smf  == null  )    {  createSelectionMotif(   )   ;}
smf.setVisible( true     )   ;
if( smf.selectA  ==  null   )  { return    ; } //annulation
String dateS     =  cpu.getMysqlDate(      abs.startD   )   ; 
String dateE     =  cpu.getMysqlDate(      abs.endD   )   ;
bpe.addMultipleDayAbsenceForEleve(    tabE    ,    dateS  ,  dateE   ,   smf.selectA .ident)    ;
jScrollPane3 .repaint( )    ; jTable2.repaint(  )    ;
    }



 }
    )  ;
             jmonth .add(jtm)   ;

    
     jtm     =  new  JMenuItem(  "Enlever Absence")    ;
  jtm.addActionListener(     new ActionListener(  )
 {
//colonne  1  =  lundi    , M1  = rang  0
    public void actionPerformed(  ActionEvent   evt)   {
semaineLS.removeAllElements()   ;tabE.removeAllElements()    ; //il  faut  +1  à  ts les index  car demarrage  à  0
int rowIndexStart = jTable2.getSelectedRow();
int colIndexEnd = jTable2.getColumnModel().getSelectionModel() .getMaxSelectionIndex(); ;
int colIndexStart = jTable2.getColumnModel().getSelectionModel().getMinSelectionIndex();
Date  [ ]  dtA     =  getMultipleMomentSelected (  rowIndexStart    ,  colIndexStart     , colIndexEnd     )    ; 

ListObj lbj  =   (ListObj) jList1.getSelectedValue()    ;
Eleve  elv  =(Eleve)lbj.value  ;  
String  dateS    = cpu.getMysqlDate(   dtA[0])    ; 
String  dateE    = cpu.getMysqlDate(   dtA[1])    ; 
bpe.removeMultipleDayAbsence(  elv  ,  dtA[0]    , dtA[1]    )   ;
//determiner   si  c'est ds une  abs dejà selectionné  ou non  




 }
  }
    )  ;

         jmonth .add(jtm)   ;



          jpm   = new  JPopupMenu(    )    ;
  jtm     =  new  JMenuItem(  "Ajouter Absence")    ;
 jtm.addActionListener(     new ActionListener(  )
 {
//colonne  1  =  lundi    , M1  = rang  0
    public void actionPerformed(  ActionEvent   evt)   {
semaineLS.removeAllElements()   ;tabE.removeAllElements()    ;
ListSelectionModel lsm  =  jTable1.getSelectionModel(   )    ;
int rowIndexStart = jTable1.getSelectedRow();
int rowIndexEnd = lsm.getMaxSelectionIndex();
int colIndexStart = jTable1.getSelectedColumn();
int colIndexEnd = lsm.getMaxSelectionIndex(); // Check each cell in the range
Absence abs   = new Absence(   )  ;
 abs.daypos     =colIndexStart +1 ; abs.startR  =rowIndexStart    ;abs.endR  =rowIndexEnd    ;
System.out.println ( "absence "+rowIndexStart+"  "+  rowIndexEnd  )   ;
setDayAbsenceSelected(  abs    )   ;
 semaineLS.add(     abs   )    ;    //eleves selected
Object  [ ]  tabO   = jList1.getSelectedValues()    ;ListObj lbj   ;
     for (     int  p = 0  ; p<tabO.length  ;p++)   {
            lbj   =   (ListObj)tabO[p]    ;
             tabE.add(   lbj.value     )      ;
     }
//demander le motif et inserer  en abs
if(  smf  == null  )    {  createSelectionMotif(   )   ;}
smf.setVisible( true     )   ;
if( smf.selectA  ==  null   )  { return    ; } //annulation
String  motif   = smf.selectA.ident    ; 
String dateS    =  cpu.getMysqlDate(    abs.startD  )    ;
bpe.addDayAbsenceForEleve1(tabE,  dateS  , abs.startHS   , abs.endHS     ,    motif);
    }
  


 }
    )  ;


jpm.add(    jtm    )   ;
     
         
         
          
      }

public void setMultipleDayAbsence(  Absence abs     ,   int  rowindex  , int colindexS     , int colindexE   )   {
Month mth     =  getMonth  (     rowindex    )     ;
gcd.set(Calendar.MONTH      ,     mth.position-1    )     ;  gcd.set ( Calendar.DAY_OF_MONTH   ,  colindexS        )       ;
if(    mth.position > 12 )  {  gcd.set(Calendar.YEAR, anneeI[1]      )        ;   }
else  {   gcd.set(Calendar.YEAR,  anneeI[0]    )        ;  }
abs.  startD    =  gcd.getTime(    )    ;
 gcd.set ( Calendar.DAY_OF_MONTH   ,  colindexE )  ;
 abs.endD     = gcd.getTime(  )   ;

}

public   Date [ ] getMultipleMomentSelected ( int  rowindex  , int colindexS     , int colindexE    )   {
    Date [] dtA    =new Date[2]     ;
   Month mth     =  getMonth  (     rowindex    )     ;
gcd.set(Calendar.MONTH      ,     mth.position-1    )     ;  gcd.set ( Calendar.DAY_OF_MONTH   ,  colindexS      )       ;
if(    mth.position > 12 )  {  gcd.set(Calendar.YEAR, anneeI[1]     )        ;   }
else  {   gcd.set(Calendar.YEAR, anneeI[0]   )        ;  }
dtA[0]    =  gcd.getTime(    )    ;
 gcd.set ( Calendar.DAY_OF_MONTH   ,  colindexE )  ;
dtA[1]     = gcd.getTime(  )   ;
 return dtA    ; 
}


 public  Month   getMonth  ( int    rowindex    )   {
  Month  mth     ;
     for(  int  s   = 0  ;s<monthL.size( )  ;s++ )  {
  mth     =(Month)monthL.elementAt(  s   )     ;
  if(  mth.indexposition    ==   rowindex     ) {  return mth    ;}
     }
  return null    ;
 }
      
public   void setDayAbsenceSelected(   Absence abs    )   {
   ListObj lbj  =(ListObj)jComboBox3.getSelectedItem()     ;
   Week wk    =(    Week)lbj.value    ;
abs.startD     = cpu.findDateInWeek (    wk ,  abs.daypos     )   ;
abs.endD    =   abs.startD     ;
abs.startHS    =     (String)tabMH.get(   new  Integer( abs.startR )   )     ;
System.out.println (  "endR :"+abs.endR     )    ;abs.endHS    =     (String)tabMHS.get(   new  Integer( abs.endR )   )     ;

}

      public void  showEleves(  )  {
     ListObj  lbj  =(ListObj)jComboBox4.getSelectedItem()   ;
   Classe  cls    =(Classe)lbj.value    ;listemodel.removeAllElements();   
  Vector  tab  =  bpe. getClasse(    cls.classeid    )   ;
cpu.addList( tab   ,  listemodel  ,ConstantFolder.eleve  )   ;

 }


    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {
        java.awt.GridBagConstraints gridBagConstraints;

        jSplitPane1 = new javax.swing.JSplitPane();
        jTabbedPane1 = new javax.swing.JTabbedPane();
        jScrollPane2 = new javax.swing.JScrollPane();
        jTable1 = new javax.swing.JTable();
        jScrollPane3 = new javax.swing.JScrollPane();
        jTable2 = new javax.swing.JTable();
        jScrollPane1 = new javax.swing.JScrollPane();
        jList1 = new javax.swing.JList();
        jPanel2 = new javax.swing.JPanel();
        jButton1 = new javax.swing.JButton();
        jComboBox1 = new javax.swing.JComboBox();
        jComboBox2 = new javax.swing.JComboBox();
        jPanel5 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jComboBox3 = new javax.swing.JComboBox();
        jPanel6 = new javax.swing.JPanel();
        jLabel2 = new javax.swing.JLabel();
        jComboBox4 = new javax.swing.JComboBox();

        setMinimumSize(new java.awt.Dimension(600, 400));
        setPreferredSize(new java.awt.Dimension(600, 400));
        setLayout(new java.awt.BorderLayout());

        jSplitPane1.setResizeWeight(0.6);

        jTable1.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {"M1", null, null, null, null, null},
                {"M2", null, null, null, null, null},
                {"M3", null, null, null, null, null},
                {"M4", null, null, null, null, null},
                {"S1", null, null, null, null, null},
                {"S2", null, null, null, null, null},
                {"S3", null, null, null, null, null},
                {"S4", null, null, null, null, null},
                {null, null, null, null, null, null}
            },
            new String [] {
                "Horaire", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"
            }
        ) {
            Class[] types = new Class [] {
                java.lang.String.class, java.lang.Object.class, java.lang.Object.class, java.lang.Object.class, java.lang.Object.class, java.lang.Object.class
            };
            boolean[] canEdit = new boolean [] {
                false, true, true, true, true, true
            };

            public Class getColumnClass(int columnIndex) {
                return types [columnIndex];
            }

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit [columnIndex];
            }
        });
        jTable1.setCellSelectionEnabled(true);
        jTable1.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                jTable1MousePressed(evt);
            }
        });
        jScrollPane2.setViewportView(jTable1);

        jTabbedPane1.addTab("tab2", jScrollPane2);

        jTable2.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {"Aout", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Septembre", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Octobre", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Novembre", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Decembre", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Janvier", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Fevrier", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Mars", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Avril", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Mai", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Juin", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null},
                {"Juillet", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null}
            },
            new String [] {
                "Horaire", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "15", "16", "17", "18", "19", "20", "21", "22", "23", " 24", "25", "26", "27", " 28", "29", "30", "31"
            }
        ));
        jTable2.setCellSelectionEnabled(true);
        jTable2.setMinimumSize(new java.awt.Dimension(465, 600));
        jTable2.setPreferredSize(new java.awt.Dimension(2325, 600));
        jTable2.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                jTable2MousePressed(evt);
            }
        });
        jScrollPane3.setViewportView(jTable2);

        jTabbedPane1.addTab("tab3", jScrollPane3);

        jSplitPane1.setLeftComponent(jTabbedPane1);

        jList1.setModel(new javax.swing.AbstractListModel() {
            String[] strings = { "Item 1", "Item 2", "Item 3", "Item 4", "Item 5" };
            public int getSize() { return strings.length; }
            public Object getElementAt(int i) { return strings[i]; }
        });
        jList1.addListSelectionListener(new javax.swing.event.ListSelectionListener() {
            public void valueChanged(javax.swing.event.ListSelectionEvent evt) {
                jList1ValueChanged(evt);
            }
        });
        jScrollPane1.setViewportView(jList1);

        jSplitPane1.setRightComponent(jScrollPane1);

        add(jSplitPane1, java.awt.BorderLayout.CENTER);

        jPanel2.setLayout(new java.awt.GridBagLayout());
        jPanel2.add(jButton1, new java.awt.GridBagConstraints());

        jComboBox1.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "Item 1", "Item 2", "Item 3", "Item 4" }));
        jPanel2.add(jComboBox1, new java.awt.GridBagConstraints());

        jComboBox2.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "Item 1", "Item 2", "Item 3", "Item 4" }));
        jPanel2.add(jComboBox2, new java.awt.GridBagConstraints());

        jPanel5.setLayout(new java.awt.GridBagLayout());
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        jPanel5.add(jLabel1, gridBagConstraints);

        jComboBox3.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jComboBox3ActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        jPanel5.add(jComboBox3, gridBagConstraints);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridy = 1;
        jPanel2.add(jPanel5, gridBagConstraints);

        jPanel6.setLayout(new java.awt.GridBagLayout());
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 0;
        gridBagConstraints.gridy = 1;
        jPanel6.add(jLabel2, gridBagConstraints);

        jComboBox4.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "Item 1", "Item 2", "Item 3", "Item 4" }));
        jComboBox4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jComboBox4ActionPerformed(evt);
            }
        });
        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridx = 1;
        gridBagConstraints.gridy = 1;
        jPanel6.add(jComboBox4, gridBagConstraints);

        gridBagConstraints = new java.awt.GridBagConstraints();
        gridBagConstraints.gridy = 1;
        jPanel2.add(jPanel6, gridBagConstraints);

        add(jPanel2, java.awt.BorderLayout.NORTH);
    }// </editor-fold>//GEN-END:initComponents

    private void jTable1MousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_jTable1MousePressed
        System.out.println("Ok ok ")   ;
        if( evt.getButton()  == evt.BUTTON3       )  {
            jpm .show(this.jTable1   , evt.getX( )  ,  evt.getY()    );
        }
}//GEN-LAST:event_jTable1MousePressed

    private void jTable2MousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_jTable2MousePressed
        System.out.println("Ok ok ")   ;
        if( evt.getButton()  == evt.BUTTON3       )  {
            jmonth .show(this.jTable2   , evt.getX( )  ,  evt.getY()    );
        }
}//GEN-LAST:event_jTable2MousePressed

    private void jList1ValueChanged(javax.swing.event.ListSelectionEvent evt) {//GEN-FIRST:event_jList1ValueChanged
        if( initialised  )   {    showAbsencesInPanelSemaine(   )  ; showAbsencesInPanelMonth()   ;  }
}//GEN-LAST:event_jList1ValueChanged

    private void jComboBox3ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jComboBox3ActionPerformed
        // if( initialised  )   {  setAbsencesInPanel(   )   ;       }  ;    // TODO add your handling code here:
}//GEN-LAST:event_jComboBox3ActionPerformed

    private void jComboBox4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jComboBox4ActionPerformed
        if( initialised  )   {  showEleves(  )   ;}
}//GEN-LAST:event_jComboBox4ActionPerformed

    public void  showDPAbsents(  )  {

}



public  void   showAbsencesInPanelSemaine(   )   {  //absences du premier selectionnné 
ListObj    lbj  =(ListObj)jList1.getSelectedValue()   ; cep.indexH.clear()    ; jTable1.repaint(); 
if(    lbj  ==  null   )   { return   ;}  
Eleve elv =(Eleve)lbj.value   ;System.out.println ( "set abse,nces in panel :"+elv.nom )  ;
lbj  =(ListObj)jComboBox3.getSelectedItem()     ;Absence abs   ;
   Week wk    =(    Week)lbj.value    ;  int  colindex  ,   colindex1 ; 
   gcd.setTime( wk.startD   )  ;Date  dt   =   wk.startD    ;String dateS    ; int  []    tab  ; Vector tabA  = new Vector(   )    ; 
      bpe.getDayAbsenceForEleve1(  elv.id   , wk.startD   , wk.endD  ,  tabA   )    ;System.out.println (    "tabA :"+tabA     )   ; 
     for(    int  s  = 0  ;s<tabA.size()    ;s++)   {
 abs  =(Absence)tabA.elementAt(    s  )   ;    
 //si   startD   et differnet  de endD    c'est une abs   pour plusieurs  jours    ... 
 if(    cpu.isDateSame(    abs.startD    , abs.endD  ) )   {
 tab  =   getRowForAbsences(       abs  )    ;cep.fullrow   =   false        ;
colindex    = getColumnForAbsence(   abs  )    ;
for (   int  p =tab[0]   ; p<tab[1]+1  ;p++)   {
 cep.indexH.put(     new Integer(   p   )     ,  new Integer( colindex   )   )  ;
       
}    
 }else   {   //ts les rangees   entre le  2 j selectioné 
colindex    = getColumnForAbsence(    abs.startD       )      ; 
  colindex1    = getColumnForAbsence(    abs.endD        )      ;
  Enumeration ep  ; Integer  it   =null   ; cep.indexL.removeAllElements(  )   ;
  for(  int    p =   colindex   ; p<colindex1+1    ;  p++)   {
   cep. indexL.add(     new Integer(   p     )       )    ;cep.fullrow   = true     ; 
   
  }

 }
 
      
     }
     jTable1.repaint();
}




public int   getColumnForAbsence(Date  dt     )   {
 gcd.setTime(   dt    )     ;
int  pos   =  gcd.get  (Calendar.DAY_OF_WEEK  )       ;
return  pos-1    ;
}

public int   getColumnForAbsence( Absence  abs  )   {
 gcd.setTime(    abs.startD  )     ;
int  pos   =  gcd.get  (Calendar.DAY_OF_WEEK  )       ;
return  pos-1    ;
}

public int  []       getRowForAbsences(  Absence  abs  )  {
 int []         tab  = new   int[2]    ; //Vector( )     ;  //int[2]  ;
Enumeration ep    =   tabMH .keys(  )   ;Integer  it   =null   ; String valS    ;  boolean ok  = true   ;
while (  ep.hasMoreElements() &&  ok    )  {
 it  =(Integer)ep.nextElement(    )    ;
 valS  =(String)tabMH.get(    it   )    ;System.out.println( "startHS :"+abs.startHS +" valS :"+valS  )    ;
 if( valS.equals( abs.startHS )   ) {    ok  = false   ;}
}
tab[0] =   it.intValue()        ;  ok  = true    ;
 ep    =   tabMHS .keys(  )    ;
while (  ep.hasMoreElements() &&  ok    )  {
 it  =(Integer)ep.nextElement(    )    ;
 valS  =(String)tabMHS.get(    it   )    ;
 if( valS.equals( abs.endHS   )   ) {    ok  = false   ;}
}
tab[1] =    it.intValue(  )   ;  return tab   ;
 }

 public void showAbsencesInPanelMonth()    {
     ListObj   lbj  =(ListObj)jList1.getSelectedValue() ;  int  s  ;cmc.indexH.clear();   
     if(  lbj  ==  null  ) { return    ;}  
    Eleve elv =(Eleve)lbj.value    ;Vector tab   ; Month mth    ;String startDS  , endDS  , valS ;
    for(    s  =   0 ; s<monthL.size( )    ;s++ )  {
mth  =(Month)monthL.elementAt( s   )     ; tab  = new Vector(  )   ;
if( mth.position < 10  )   { valS   = "0"+mth.  position   ;} 
else   { valS   = ""+mth.  position    ;  }  
        startDS  =  mth.yearS+"-"+valS +"-01"  ;
 endDS   =  mth.yearS+"-"+valS +"-"+mth.nbdays   ;
   bpe.showAbsencesForEleve1(  elv.id     , startDS      , endDS      ,   tab      )       ; /// +  les abs  ds  tab 
absH.  put (  new Integer(  mth.indexposition )   , tab    )   ;
    }

   for(   int h   =   0 ; h<monthL.size( )    ;h++ )  {
mth  =(Month)monthL.elementAt(   h   )     ;
  tab   = (Vector)absH.get(    mth.indexposition  )   ;Vector  tabCI   = new  Vector(   )    ;
     Absence  abs   ;  int  startindex  , endindex     ;System.out.println(  "selectionEleve :"+ tab   )   ;
        for(   s  = 0  ; s<tab.size()   ;s++  )  {
abs  =(Absence)tab.elementAt(  s )   ;
  gcd.setTime ( abs.startD  )  ;
startindex       =gcd. get ( Calendar.DAY_OF_MONTH       )     ;//System.out.println(  "selectionEleve :"+ startindex    )   ;//demarrage à   1  sur la table
gcd.setTime ( abs.endD  )  ;
endindex       =gcd. get ( Calendar.DAY_OF_MONTH   )      ;
int  [ ]  tabI   = new int [2 ]    ;
tabI[0]    = startindex  ; tabI[1]    = endindex    ;tabCI.add( tabI  )    ;

        }
     cmc.indexH.put(   mth.indexposition    ,   tabCI    )   ;System.out.println(  "put indexH indexposition:"+ mth.indexposition  +" tabCI:"+tabCI      )   ;


   }
  
     jTable2.repaint();
      //    cmc.indexH.clear(  )    ; 
     
 }

 public  void  createSelectionMotif(   )  {
smf  = new SelectionMotif(   parentF    , true )   ;smf.rendererL    =rendererL  ; smf.cpu  = cpu  ;smf.motifL  =motifL   ;
smf.init()   ;
 }
    
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton jButton1;
    private javax.swing.JComboBox jComboBox1;
    private javax.swing.JComboBox jComboBox2;
    private javax.swing.JComboBox jComboBox3;
    private javax.swing.JComboBox jComboBox4;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JList jList1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel5;
    private javax.swing.JPanel jPanel6;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JSplitPane jSplitPane1;
    private javax.swing.JTabbedPane jTabbedPane1;
    private javax.swing.JTable jTable1;
    private javax.swing.JTable jTable2;
    // End of variables declaration//GEN-END:variables
}
