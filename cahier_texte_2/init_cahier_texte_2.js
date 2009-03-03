
//page initialisation
Event.observe(window, 'load', initPage);

function initPage () {
	getWinCalendar();
	getWinDernieresNotices().show();
}


function include(filename)
{
	var head = document.getElementsByTagName('head')[0];
	//alert('test : ' + head);
	
	script = document.createElement('script');
	script.src = filename;
	script.type = 'text/javascript';
	
	head.appendChild(script);
}

include('./webtoolkit.aim.js');
include('./ajax_functions.js');
include('./calendar/calendar.js');
include('./calendar/lang/calendar-fr.js');
include('./calendar/calendar-setup.js');
include('../fckeditor/fckeditor.js');
include('../edt_effets/javascripts/window.js');

function getWinListeNotices() {
	if (typeof winListeNotices=="undefined") {
		winListeNotices = new Window(
				{id: 'win_liste_notices',
				title: 'Liste des Notices',
				showEffect: Element.show,
				top:110,
				left:0,
				width:330,
				height:GetHeight() - 160}
			);
		$('win_liste_notices_content').setStyle({	
			backgroundColor: '#d0d0d0',
			fontSize: '12px',
			color: '#000000'
		});
		winListeNotices.getContent().innerHTML= "<div id='affichage_liste_notice' style='padding: 3px'><div>";
	}
	winListeNotices.show();
	winListeNotices.toFront();
	return winListeNotices;
}

function getWinEditionNotice() {
	if (typeof winEditionNotice=="undefined") {
		winEditionNotice = new Window(
				{id: 'win_edition_notice',
				title: 'Edition de Notice',
				showEffect: Element.show,
				top:110,
				left:334,
				width:GetWidth()-360,
				height:GetHeight() - 160}
			);
		$('win_edition_notice_content').setStyle({	
			backgroundColor: '#d0d0d0',
			fontSize: '14px',
			color: '#000000'
		});
	}
	winEditionNotice.show();
	winEditionNotice.toFront();
	return winEditionNotice;
}

function getWinDernieresNotices() {
	if (typeof winDernieresNotices=="undefined") {
		winDernieresNotices = new Window(
				{id: 'win_dernieres_notices',
				title: 'Derni�res Notices',
				showEffect: Element.show,
				hideEffect: Element.hide,
				top:105,
				left:40,
				width:GetWidth()-100,
				height:GetHeight() - 170}
			);
		$('win_dernieres_notices_content').setStyle({	
			backgroundColor: '#d0d0d0',
			fontSize: '14px',
			color: '#000000'
		});
		winDernieresNotices.getContent().innerHTML= "<div id='affichage_derniere_notice'><div>";
		// Set up a windows observer to refresh the window when focused
		var  myObserver = { onFocus: function(eventName, win) {
			 	if (win == winDernieresNotices) {
			 		new Ajax.Updater('affichage_derniere_notice', 'ajax_affichage_dernieres_notices.php');
			 		//win.setAjaxContent('ajax_affichage_dernieres_notices.php');
			 	}
		 	}
		 }
		 Windows.addObserver(myObserver);
		 winDernieresNotices.show();
	}
	return winDernieresNotices;
}

function getWinCalendar() {
	if (typeof winCalendar=="undefined") {
		winCalendar = new Window(
				{id: 'win_calendar',
				title: 'Calendrier',
				closable: false,
				minimizable: false,
				maximizable: false,
				showEffect: Element.show,
				top:0, 
				right:85,
				width:154,
				height:150}
			);
		$('win_calendar_content').setStyle({	
			backgroundColor: '#d0d0d0',
			color: '#000000'
		});
		$('win_calendar_content').innerHTML = '<div id="calendar-container-2" onmouseover="winCalendar.toFront();">';
		calendarInstanciation = Calendar.setup(
				{
					flat         : "calendar-container-2", // ID of the parent element
					flatCallback : dateChanged,          // our callback function
					daFormat     : "%s",    			   //date format
					weekNumbers  : false,
					dayMouseOverCalendarToFront : true
				}
			);
	}
	winCalendar.show();
	winCalendar.toFront();
	return winCalendar;
}

function GetWidth()
{
        var x = 0;
        if (self.innerHeight)
        {
                x = self.innerWidth;
        }
        else if (document.documentElement && document.documentElement.clientHeight)
        {
                x = document.documentElement.clientWidth;
        }
        else if (document.body)
        {
                x = document.body.clientWidth;
        }
        return x;
}

function GetHeight()
{
        var y = 0;
        if (self.innerHeight)
        {
                y = self.innerHeight;
        }
        else if (document.documentElement && document.documentElement.clientHeight)
        {
                y = document.documentElement.clientHeight;
        }
        else if (document.body)
        {
                y = document.body.clientHeight;
        }
        return y;
}