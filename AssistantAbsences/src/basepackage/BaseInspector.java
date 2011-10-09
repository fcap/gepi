/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package basepackage;
import java.sql.* ; import  java.util.GregorianCalendar ;import java.util.Date   ;
import  java.util.Calendar  ;import  java.util.Vector  ;import  java.util.Hashtable  ;

/**
 *
 * @author root
 */
public class BaseInspector {
public Connection selectC   , selectGC ;public Hashtable   baseinfoH      ;
public   StringBuffer  bitim     ;  public CPEUtility  cpu   ;  public GregorianCalendar gcd   ;
public String ident  , passwd    , dbname    ;    public int port     ;

public BaseInspector( )  {
   bitim  = new StringBuffer(  )   ;  gcd  = new GregorianCalendar( )   ;
}

 public void initCreneauHoraire(  Hashtable tabMH    , Hashtable tabMHS  )  {
     String query   ="SELECT * FROM `edt_creneaux`"     ; 
    ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;  int index    ; 
    java.sql.Time   startH    , endH     ; 
            
            try  {
    while(   rst.next(  ) ) {
 index    =     rst.getInt(        1   );   
 startH  = rst.getTime(     3  )    ;   endH  = rst.getTime(      4    )    ;  
  
 tabMH.put(    new Integer(  index-1 ),  cpu.getMysqlTime(      startH   )        )    ; tabMHS.put(    new Integer(  index-1 ),  cpu.getMysqlTime(      endH    )        )    ; 
 
 }
 }catch (SQLException ep  ) {   System. out. println( ep  )   ;}   

 }


public void   readMotifAbsence ( Vector  tab   )   {
    String query ="select *  from   `absences_motifs` "   ;AbsenceMotif amf    ;
    ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;
 try  {
    while(   rst.next(  ) ) {
 amf   = new AbsenceMotif(  )  ;
 amf.ident   = rst.getString(  2 ) ;  amf.motif  = rst.getString(  3 )   ;
 tab.add(  amf  )     ;
 }
 }catch (SQLException ep  ) {   System. out. println( ep  )   ;}
}

  public Vector  getDayOff(    )    {
   Vector tab  =   new Vector(  )    ;
      String query  ="select  DISTINCT  `jourdebut_calendrier`  ,  `jourfin_calendrier`  from   `edt_calendrier`  "  ;  //where  ( `d_date_absence_eleve`  >= '"+startDS+"' and `a_date_absence_eleve` <= '"+endDS+"' )  AND `eleve_absence_eleve`='"+loginE+"'" ; //2010-04-12' "   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;Holyday   hdy      ;
try  {
while ( rst.next(  ) )   {
hdy  = new Holyday(  )    ;   hdy.startD  =   rst.getDate( 1 )    ;  hdy.endD  =    rst.getDate( 2  )   ;
tab.add(  hdy  )   ;
 
}
}catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return tab   ;
  }
  
  
  public void addDayAbsenceForEleve1(  Vector tab    ,String  dateS  ,  String startH    , String endH  , String motif )   {  //+ ds   a_saisie 
String query    ;String  logE       ; Eleve elv     ;
String  valS1     =   dateS+" "+startH   ;   String  valS2     =   dateS+" "+endH   ;  

String  nowDS    =cpu.getActualDateTime(     )        ;  ///null    ; //date time sur    la  date du jour   , ou prendre  date serveur  
      for( int  s   =  0 ; s<tab.size( )    ;s++)   {
          elv   = (Eleve)tab.elementAt( s )  ;
      
         
          //il faut  metttre   l'id de leeleve 
          query  ="insert   into  `a_saisies` ( `utilisateur_id`  , `eleve_id`   , `debut_abs`   , `fin_abs`,    "    ;
query  +=  " `id_classe`   , `created_at`        )  VALUES   (  "    ;
query   +="'cpe' , "+ elv.id+" , '"+valS1+"' ,'"+valS2+"',"+elv.idclasse+" ,'"+nowDS+"'   ) "    ;
System.out.println (   "query  :"+query   )    ;
executeQuery( query ,  ConstantFolder.gepi )    ;
}
  }
  
   public  void  getDayAbsenceForEleve1(   int  eleveid         , Date  wSD    , Date  wED     , Vector tab        )  { //entre les  2 dates 
String  dtS1  =  getMysqlDate( wSD  )   ; String  dtS2  =  getMysqlDate( wED  )   ; 
       String query  ="select  `debut_abs` , `fin_abs`      from `a_saisies`  where  `eleve_id`="+eleveid +" and (    `debut_abs` >='"+dtS1+"' AND  `fin_abs` <='"+dtS2+"'  )" ; // (  `eleve_absence_eleve`='"+logE+"' and  `d_date_absence_eleve`='"+dateS+"'     ) " ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;Absence abs   ; Date startD     , endD       ;   java.sql.Time   startT    , endT    ; 

try  {
while(  rst  .next(  )   )  {
 startD =  rst.getDate(   1  )   ;  endD    = rst.getDate(    2   )    ;System.out.println (  "week start:"+wSD+" week endd:"+wED )   ;
 startT    =  rst.getTime(      1 )    ;  endT    =  rst.getTime(       2  )    ; 
 //utiliser du texte   pour les date de semaine 
// if(  ( cpu.isDateSame(   startD    ,   wSD          )     ||    startD .before (wSD   )       )   &&   (  cpu.isDateSame(  wED  ,  endD   )  && endD.before ( wED      )) )    {
 //if(                             ) 
 abs   = new Absence(  )   ;abs.startD    =   startD      ;abs.endD   = endD     ;abs.startT    = startT    ;abs.endT   = endT   ; 
 abs.startHS    =  cpu.getMysqlTime(  abs.startT   )    ;  abs.endHS   =  cpu.getMysqlTime(  abs.endT  )    ;  
tab.add( abs  )    ;
 //}


}
}catch (SQLException ep  ) {   System. out. println( ep  )   ;}

    }
   
    public void  showAbsencesForEleve1( int  eleveid  , String startDS    , String endDS   , Vector tab      )    {   //vue mensuelle   , periodique 
  String query  ="select  `debut_abs` , `fin_abs`      from `a_saisies` where  `eleve_id`="+eleveid +" AND  (  `debut_abs`  >= '"+startDS+"' and  `fin_abs` <= '"+endDS+"' )  " ; //2010-04-12' "   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;Absence  abs   ;
try  {
while ( rst.next(  ) )   {
abs   = new Absence( )   ;   //abs.loginE    =  loginE    ;
abs.startD   = rst.getDate( 1    )    ; abs.endD    =rst.getDate(    2    )    ;tab.add(   abs   )    ;  //1 h  d'abs  ds  une journée    fait  apparait absent  ds le tableau mensue
}
}catch (SQLException ep  ) {   System. out. println( ep  )   ;}
}

   
 

  public void addMultipleDayAbsenceForEleve(  Vector tab    ,String  dateS  ,  String dateE   , String motif )   {  //à  voir
String query    ;String  logE       ; Eleve elv     ;
String  startH ="07:30:00"     ;String endH   ="17:00:00"   ;
String   valS1     =    dateS+" "+startH    ; 
String   valS2     =    dateE+" "+endH    ; 
String  nowDS    =cpu.getActualDateTime(     )        ;
      for( int  s   =  0 ; s<tab.size( )    ;s++)   {
          elv   = (Eleve)tab.elementAt( s )  ;
          logE    =elv.loginG    ;
          
            query  ="insert   into  `a_saisies` ( `utilisateur_id`  , `eleve_id`   , `debut_abs`   , `fin_abs`,    "    ;
query  +=  " `id_classe`   , `created_at`        )  VALUES   (  "    ;
query   +="'cpe' , "+ elv.id+" , '"+valS1+"' ,'"+valS2+"',"+elv.idclasse+" ,'"+nowDS+"'   ) "    ;
      System.out.println(  "query :"+query  )   ;
executeQuery( query ,  ConstantFolder.gepi )    ;    
               
      
}
  }

   public void addMultipleDayAbsenceForEleve(  Eleve elv      ,String  dateS  ,  String dateE   , String motif )   {  //à  voir
String query    ;String  logE       ;  
String  startH ="07:30:00"     ;String endH   ="17:00:00"   ;
String   valS1     =    dateS+" "+startH    ; 
String   valS2     =    dateE+" "+endH    ; 
String  nowDS    =cpu.getActualDateTime(     )        ;
       query  ="insert   into  `a_saisies` ( `utilisateur_id`  , `eleve_id`   , `debut_abs`   , `fin_abs`,    "    ;
query  +=  " `id_classe`   , `created_at`        )  VALUES   (  "    ;
query   +="'cpe' , "+ elv.id+" , '"+valS1+"' ,'"+valS2+"',"+elv.idclasse+" ,'"+nowDS+"'   ) "    ;
    System.out.println(  "query :"+query  )   ;
//executeQuery( query ,  ConstantFolder.gepi )    ;           
              
          
  }

  public boolean    removeMultipleDayAbsence( Eleve  elv  ,Date   startD     ,  Date  endD            ) {  //endDA   :date de  fin de l'abs   !!l'abs  peut ne  pas demarre  à dateS
 String  dateS    = cpu.getMysqlDate(  startD  )    ; 
String  dateE    = cpu.getMysqlDate(   endD   )    ; 
      String  startH ="07:30:00"     ;String endH   ="17:00:00"   ;
String   valS1     =    dateS+" "+startH    ; 
String   valS2     =    dateE+" "+endH    ; 
//on  regarde  si on a  une absen,ces ds  ces bornes  
      String  query  ="select `debut_abs`   , `fin_abs`, `id`   from    `a_saisies` where  `eleve_id`="+elv.id  +"  and  (  `debut_abs`<='"+valS1+"'  and  `fin_abs`>='"+valS2 +"' ) "   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;String motif ;
try  {
  if (  rst.next(  ) )  {  //il y en a une
//il faut   enlever   l'absence  juste entre les  2 dates 
    Date   dt1  =    rst.getDate(      1    )    ;     Date   dt2  =    rst.getDate(     2     )    ;  
    int id   = rst.getInt(    3  )    ;
     
    if(  cpu.isDateSame( dt1 , startD )  &&   cpu.isDateSame( dt2 , endD   )  )   {
   query  ="  delete from    `a_saisies` where `eleve_id`="+elv.id  +"  and  (  `debut_abs``='"+valS1+"'  and  `fin_abs`='"+valS2 +"' ) " ;  
   //voir   à   enlever ts ce qui est associé  à cet abs 
    }
    else  {
    if(  cpu.isDateSame( dt1 , startD )    )  { //on  fait une mise à jour de  l'abs  
//il  reste une   partie de l'abs    à  droite  , on en inserer  une nlle    ,  date de depart  1  jour  apres endD     ...   , date de fin  : fin de  la precedente abs 
   gcd.setTime(   endD    )    ;      gcd.add(Calendar.DATE   , 1 )    ;    endD    =   gcd.getTime(     )    ;  
   valS1  =  cpu.getMysqlDate(  endD      )   +startH     ; 
   //pas  de motif  pour une saisie  
      query  ="update  `a_saisies`   set    `debut_abs`='"+valS1+"'  where   `id`="+id   ; 
    }  
    
    if(  cpu.isDateSame( dt2 , endD )    )  {
//il  reste une   partie de l'abs    à  droite  , on en inserer  une nlle    ,  date de depart  1  jour  apres endD     ...   , date de fin  : fin de  la precedente abs 
   gcd.setTime(   endD    )    ;      gcd.add(Calendar.DATE   , 1 )    ;    endD    =   gcd.getTime(     )    ;  
   valS1  =  cpu.getMysqlDate(  endD      )   +startH     ; 
   //pas  de motif  pour une saisie  
       query  ="update  `a_saisies`   set    `fin_abs`='"+valS1+"'  where   `id`="+id   ;   
    }      
        
    }
     
  System.out.println (   query     )    ; 
      // executeQuery(    query    , ConstantFolder.gepi     )   ;//on -  l'abs

   return true     ;
}
  return false     ;
}catch (SQLException ep  ) {   System. out. println( ep  )   ;  }
  return false     ;
  }




    public  Absence getDayAbsenceForEleve(  String logE    , String dateS   )  {
String query  ="select  `d_heure_absence_eleve` , `a_heure_absence_eleve`   , `motif_absence_eleve`  from `absences_eleves`  where   (  `eleve_absence_eleve`='"+logE+"' and  `d_date_absence_eleve`='"+dateS+"'     ) " ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;Absence abs   ; 
try  {
if(  rst  .next(  )   )  {
abs   = new Absence(  )   ;abs.startT    = rst.getTime(   1 )   ;abs.endT  = rst.getTime(   2  )   ;
abs.motif  = rst.getString(   3 )   ;
return abs     ;
}
}catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return null    ;
    }




//absents

public void showDPAbsents (  String  dateS   )   {
String query  ="select  * from   `j_regime_eleves` "   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;
//ts  les dp
String nom    ;
try  {
while( rst.next(  ) )  {
nom   = rst.getString(  1 )    ;
//query   ="select   *  from   `absences_eleves`   where ( `d_date_absence_eleve` == '"+dateS+'" or     )  AND     (       )   "  ;
//il suffirait ensuite de  comptabiliser les abs    , comptage  heure/heure pour voir ce qui vienne 
}
} catch (SQLException ep  ) {   System. out. println( ep  )   ;}
}










public Vector  getAllClasse(   )   {  //recuperer  de baseclasse 
 Vector  tab   = new  Vector(  )    ;Classe   nts     ;
String query  ="select  DISTINCT `classeid`  ,`classenom`   from  `baseclasse`   "    ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gestioneleve   )  ;
        try  {
 while(  rst.next( ) )  {
     nts   =  new Classe(   )   ;
     nts.classeid    = rst.getInt(  1)   ;nts.nom  =  rst.getString  (  2   )  ;
 tab.add( nts  )    ;
 }
 return tab   ;
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return  null  ;
}

public void  desaffectClasse( int classeid   )  {
  String query  ="update  `baseclasse`  set `cpeid`=-1 where  `classeid`="+classeid       ;
executeQuery  (  query   , ConstantFolder.gestioneleve  )  ;
}

public Vector  getPersonnalClasse(  int  cpeid     )   {  //recuperer  de baseclasse
 Vector  tab   = new  Vector(  )    ;Classe   nts     ;
String query  ="select  DISTINCT `classeid`  ,`classenom`   from  `baseclasse` where  `cpeid`="+cpeid    ;
ResultSet rst    =executeRQuery  (  query   , ConstantFolder.gestioneleve  )  ;
        try  {
 while(  rst.next( ) )  {
     nts   =  new Classe(   )   ;
     nts.classeid    = rst.getInt(  1)   ;nts.nom  =  rst.getString  (  2   )  ;
 tab.add( nts  )    ;
 }
 return tab   ;
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return  null  ;
}


public Vector  getClasseForCPE(   int cpeid  )   {
 Vector  tab   = new  Vector(  )    ;Classe   nts     ;
 //IMP   on prend directement  ds la base de gepi
String query  ="select   `id`   , `classe`  from  `classes` "    ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi   )  ;
        try  {
 while(  rst.next( ) )  {
     nts   =  new Classe(  )    ;
 nts .classeid    = rst.getInt(  1 ) ;nts.nom   = rst.getString  (   2  )   ;
tab.add(  nts  )    ;

 }
 return tab   ;
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return  tab   ;
}






 public int  getMAXID(  String  col , String tbname     )   {
String query  ="SELECT  MAX("+col+" )  from   `"+tbname+"` "   ;
 ResultSet rst    =executeRQuery  (  query   , ConstantFolder.gestioneleve  )  ;
        try  {
 if(  rst.next( ) )  {
int  id   =  rst.getInt(   1 )  ;
return id  ;
  }
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
 return  -1  ;
 }

  
public  Vector  getClasse(     int  classeid   )  {
Vector  tab   = new  Vector(  )    ;Eleve elv   ;
String query  ="select *  from  `j_eleves_classes`  where `id_classe`='"+classeid+"'"   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;
        try  {
 while(  rst.next( ) )  {
elv  = new Eleve(  )    ;
elv.loginG   =    rst.getString(  1  )    ;//elv.id   
setEleveInfo(   elv    )     ;
tab.add(  elv   )   ;
 }
 return tab   ;
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
return  null  ;
}

 public void  setEleveInfo(Eleve elv    )  {
  String query  ="select   `nom` ,  `prenom` , `naissance` , `sexe`    ,  `id_eleve`    from  `eleves`  where `login`='"+elv.loginG  +"'"   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;
        try  {
if(  rst.next(  ) )  {
 elv.nom    =  rst.getString(   1 )   ;    elv.prenom    =  rst.getString(   2 )   ;   elv.birthD      =  rst.getDate(   3  )   ;
 elv.sexe    =   rst.getString(   4 )   ;elv.id     = rst.getInt(     5  )    ; 
}
 } catch (SQLException ep  ) {   System. out. println( ep  )   ;}

 }

public  void     getClasse(      int  classeid    ,Vector tab  )  {
Eleve elv   ;
String query  ="select *  from  `j_eleves_classes`  where `id_classe`='"+classeid+"'"   ;
ResultSet rst    =executeRQuery  (  query  , ConstantFolder.gepi    )  ;
        try  {
 while(  rst.next( ) )  {
elv  = new Eleve(  )    ;
elv.loginG   =    rst.getString(   1  )    ;elv.idclasse    =    classeid   ;
setEleveInfo(   elv    )     ;
tab.add(  elv   )   ;
 }

  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}

}

public String getLoginEleveINGEPI(  String nom , String prenom  )  {
String query  ="select   `login`  from  `eleves`   where   `nom`='"+nom+"' and   `prenom`='"+prenom+"'"   ;
ResultSet   rst     =   executeRQuery(  query   , ConstantFolder.gepi     )   ;
    try  {
if( rst.next()  )   {
    return  rst.getString(    1 )    ;
}
    }catch (SQLException ep  ) {   System. out. println( ep  )   ; return  null    ;  }
return  null    ;
}






 public String replaceSpecialCharacter( String textD   )   {
   bitim.delete (  0  , bitim.substring( 0 ).length( ))    ;
 int index , start  =0  ;  bitim.append ( textD   )    ;
     while    ( (index=textD.indexOf("'" ,start ) )!=-1 ){
         bitim.replace(   index, index+1, ""   )   ;
       start   =index+2     ;
        }
 return   bitim.substring(    0  )  ;
 }

  public   int getMAXIDEleve(  )  {
  String query  ="SELECT  MAX( eleveid )  from   `baseeleve` "   ;
 ResultSet rst    =executeRQuery  (  query   , ConstantFolder.gestioneleve  )  ;
        try  {
 if(  rst.next( ) )  {
int  id   =  rst.getInt(   1 )  ;
return id  ;
  }
  } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
 return  -1  ;
  }


public  static String  getMysqlDate( Date dt  ) {  //donne lengthstring qui correspond pour  mysql
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



  public void executeQuery  ( String query , int type    ) {  //query sans RS
         Connection conn   =null     ;
    if( type   == ConstantFolder.gestioneleve )   {
      if ( selectC  ==null  )  { selectC  =  getConnection(  ConstantFolder .gestioneleve  )  ;}
      conn = selectC     ;
            }
       if( type   == ConstantFolder.gepi  )   {
         if ( selectGC  ==null  )  { selectGC  =  getConnection(  ConstantFolder .gepi   )  ;}
      conn = selectGC     ;
                     }
   if(  conn  ==null  )  { return    ;}
        try  {
            Statement st   =   conn.createStatement(  ) ;
            System.out.println( "QUERY "+query ) ;
            st.executeUpdate( query   ) ;st.close(  ) ;
        } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
    }

public ResultSet executeRQuery  ( String query  , int type      ) {  //query sans RS
    Connection conn   =null     ;
    if( type   == ConstantFolder.gestioneleve )   {
      if ( selectC  ==null  )  { selectC  =  getConnection(  ConstantFolder .gestioneleve  )  ;}
      conn = selectC     ;
            }
       if( type   == ConstantFolder.gepi  )   {
         if ( selectGC  ==null  )  { selectGC  =  getConnection(  ConstantFolder .gepi   )  ;}
      conn = selectGC     ;                    
                     }
   if(  conn  ==null  )  { return  null    ;}
        try  {
 Statement st   =    conn.createStatement(  ) ;st.setQueryTimeout( 25)  ;
            System.out.println( "QUERY "+query ) ;
            ResultSet rst  =  st.executeQuery( query   ) ; return rst  ;
        } catch (SQLException ep  ) {   System. out. println( ep  )   ;}
        return null  ;
    }


public void closeConnection (   )    {
        try  {
    selectC.close(    )  ; selectC  =null    ;
        }   catch (SQLException ep  ) {  System. out.println( ep  )   ;}
}

 public boolean testConnection( String   ident   , String passwd  , String dbname     , int     port  )   {
    try  {
            DriverManager.setLoginTimeout ( 200000   ) ;
            String driver  ="com.mysql.jdbc.Driver"  ;
          Driver driverD  =   (Driver)Class.forName( driver   ).newInstance( )  ;
          DriverManager.registerDriver(driverD);String url  =null     ;   Connection con  = null   ;
          url    ="jdbc:mysql://127.0.0.1/"+dbname   ;
          selectC     = DriverManager.getConnection(url,   ident   , passwd       );
                   return true        ;
        } catch (SQLException ep  ) {  System. out.println( ep  )   ;}
        catch ( ClassNotFoundException   ep1  )  {  System. out. println( ep1  )   ;  }
        catch ( java.lang.InstantiationException ep3 )  { System. out. println( ep3  )   ;  }
        catch (  IllegalAccessException  ep4  ) { System. out. println( ep4  )   ;  }
        return false      ;
    }   
 

  public Connection  getConnection(   int type  ) {  //Connection
        try  {
            DriverManager.setLoginTimeout ( 200000   ) ;
            String driver  ="com.mysql.jdbc.Driver"  ;
          Driver driverD  =   (Driver)Class.forName( driver   ).newInstance( )  ;
          DriverManager.registerDriver(driverD);String url  =null     ;   Connection con  = null   ;
             url    ="jdbc:mysql://127.0.0.1/"+dbname     ;
           con = DriverManager.getConnection(url,    ident  , passwd   );
       
                  System. out.println( "conn "+con   )  ;      return con  ;
        } catch (SQLException ep  ) {  System. out.println( ep  )   ;}
        catch ( ClassNotFoundException   ep1  )  {  System. out. println( ep1  )   ;  }
        catch ( java.lang.InstantiationException ep3 )  { System. out. println( ep3  )   ;  }
        catch (  IllegalAccessException  ep4  ) { System. out. println( ep4  )   ;  }
        return null   ;
    }
}

