/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * PanelPeriodique.java
 *
 * Created on Apr 12, 2010, 8:31:11 AM
 */

package basepackage;
import java.awt.Color;
import javax.swing.JLabel    ;import  java.awt.GridBagConstraints    ;
import  javax.swing.JToggleButton     ;  import java.util.Vector     ;  import java.util.Hashtable   ;

/**
 *
 * @author root
 */
public class PanelMensuel extends javax.swing.JPanel {
  public Vector    monthL    ; public Hashtable   monthH     ;  //on  va faire une  hash de hash 
    /** Creates new form PanelPeriodique */
    public PanelMensuel() {
        initComponents();
    }

    public void setMonthList(   Vector tab  )  {
        monthL = tab   ;monthH  = new Hashtable(  )    ;
    }
    public  JToggleButton getToggle(  int  monthpos   , int  daypos   )   {
  Hashtable  tabH   =   (Hashtable)monthH.get(  new Integer(  monthpos  ) )  ;
  return   (JToggleButton)tabH.get( new Integer(  daypos   )  )  ;
    }

    public void init(  )   {
//ligne  du ht  de   1  à  31
/*int s  ;       JLabel  jlab  ;GridBagConstraints gbc       = new GridBagConstraints(  )   ; gbc.gridx  =1  ;  gbc.gridy   = 0   ;
        for (   s  = 1  ; s<32  ;s++ )   {
  jlab  = new JLabel(  )    ;  jlab.setText(  "" +s )    ;
     add( jlab , gbc );  gbc.gridx++  ;
        }
//lister   ts les months
gbc.gridy++   ; gbc.gridx=0  ;Month mth     ;  int p ;
for (     p   = 0  ; p<monthL.size()    ;p++ )   {
mth  =(Month)monthL.elementAt(    p   )   ;
jlab = new JLabel(   mth.abrev  )   ;    add( jlab , gbc );  gbc.gridy++  ;
}

 
  gbc.gridx  =1  ;  gbc.gridy   = 1   ;JToggleButton   jtb     ;Hashtable  tabH    ;Integer  it  ;
  for (     p   = 0  ; p<monthL.size()    ;p++ )   {
      tabH    = new Hashtable(  )   ;mth  =(Month)monthL.elementAt(    p   )   ;
for (   s  = 1  ; s<32  ;s++ )   {
   jtb  = new JToggleButton(   )    ;jtb.setText(  ""+s  )   ;
    add( jtb   , gbc );
  if(       mth.dayoff.contains(   new Integer( s )    )    ) {    jtb.setBackground(Color.white );   }
    gbc.gridx++  ;tabH.put(  s ,  jtb  )   ;
  }
      monthH .put(  mth.position  , tabH )   ; //1  , et listes des  jours  correspiondnat  
gbc.gridy++  ;  gbc.gridx  =1  ; 
  }*/
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        setMinimumSize(new java.awt.Dimension(500, 200));
        setName("Form"); // NOI18N
        setLayout(new java.awt.BorderLayout());
    }// </editor-fold>//GEN-END:initComponents


    // Variables declaration - do not modify//GEN-BEGIN:variables
    // End of variables declaration//GEN-END:variables

}
