/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * PanelSemaine.java
 *
 * Created on Apr 22, 2010, 1:08:21 AM
 */

package basepackage;
import   java.awt.event.MouseListener      ;  import java.awt.event.MouseEvent   ;import   java.awt.event.MouseMotionListener  ;

/**
 *
 * @author root
 */
public class PanelSemaine extends javax.swing.JPanel   {

    /** Creates new form PanelSemaine */
    public PanelSemaine() {
        initComponents();
    }

      public void mouseClicked(MouseEvent e)   {  }
public void mousePressed(MouseEvent e)   {

}
public void mouseReleased(MouseEvent e)   {  }
public void mouseEntered (  MouseEvent e   )   {

}
public void mouseExited (  MouseEvent e   )   {

}

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jScrollPane1 = new javax.swing.JScrollPane();
        jTable1 = new javax.swing.JTable();

        setName("Form"); // NOI18N
        setLayout(new java.awt.BorderLayout());

        jScrollPane1.setName("jScrollPane1"); // NOI18N

        jTable1.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {"M1", null, null, null, null, null},
                {"M2", null, null, null, null, null},
                {"M3", null, null, null, null, null},
                {"M4", null, null, null, null, null},
                {"S1", null, null, null, null, null},
                {"S2", null, null, null, null, null},
                {"S3", null, null, null, null, null},
                {"S4", null, null, null, null, null}
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
        jTable1.setMinimumSize(new java.awt.Dimension(90, 250));
        jTable1.setName("jTable1"); // NOI18N
        jTable1.setPreferredSize(new java.awt.Dimension(450, 250));
        jTable1.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mousePressed(java.awt.event.MouseEvent evt) {
                jTable1MousePressed(evt);
            }
        });
        jScrollPane1.setViewportView(jTable1);

        add(jScrollPane1, java.awt.BorderLayout.CENTER);
    }// </editor-fold>//GEN-END:initComponents

    private void jTable1MousePressed(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_jTable1MousePressed
    //  System.out.println ("Ok ok ")   ;
if( evt.getButton()  == evt.BUTTON2     )  {
    System.out.println ("Ok ok ")   ;
}
    }//GEN-LAST:event_jTable1MousePressed


    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTable jTable1;
    // End of variables declaration//GEN-END:variables

}
