/*
	Die Variable lib ist der Rückgabewert einer anonymen Funktion, die sofort
	nach ihrer Definition ausgeführt wird. (function (...) { ... }(...));
	
	lib steht in anderen JavaScripten zur Verfügung, wenn die Datei
	lib.js mit Hilfe von <script src=".../lib.js"></script> inkludiert wird.
	
	Der einzige Zweck dieser anonymen Funktion ist die Definition der Funktionen,
	aus denen die Bibliothek besteht. Sie ist sozusagen ein Generator für die
	Bibliotheksfunktionen.
	
	Weil der Scope von JavaScript Variablen auf die Funktion beschränkt ist, in der 
	sie deklariert werden, kann es außerhalb der anonymen Funktion nicht zu Namenskonflikten
	kommen.
*/

var lib = (function(win) {

	// Canvas helpers

	// Gibt eine Zeichenkontext für den Canvas cs zurück
	function getContext(cs) {
		let c = cs.getContext('2d');
		c.beginPath();
		return c;
	};

	// Benutzt den Zeichenkontext c, um das Rechteck (0,0) - (w,h) vom 
	// Canvas zu löschen
	function clearCanvas(c, x, y, w, h) {
		c.clearRect(x, y, w, h);
		c.beginPath();
	};

	// Benutzt den Zeichenkontext c, um eine gerade Linie vom Punkt (x1,y1) 
	// bis zum Punkt (x2,y2) zu zeichnen
	function drawLine(c, x1, y1, x2, y2) {
		c.moveTo(x1, y1);
		c.lineTo(x2, y2);
		c.stroke();
	};
					
	// Benutzt den Zeichenkontext c, um einen Kreisbogen mit Radius r 
	// auf den Canvas zu zeichnen, (x,y) ist der Mittelpunkt, a0 und a1
	// sind der Anfangs- bzw. der Endwinkel
	function drawArc(c, x, y, r, a0, a1) {
		c.arc(x, y, r, a0, a1);
		c.stroke();
	};
					
	// Benutzt den Zeichenkontext c, um den String str mit dem Font font 
	// beginnend beim Punkt (x,y) auf den Canvas zu schreiben 
	function writeText(c, font, str, x, y) {
		c.font = font;
		c.fillText(str, x, y);
	};		
			
	/////////////////////////////////////////////////////

	// Ajax helpers

	/*
		- Erzeugt ein XMLHttpRequest Objekt und gibt es zurück
		- Bindet die Funktionen success und error an das XMLHttpRequest Objekt
		- success und error sind hier die Funktionen, die beim Aufruf von ajaxCreateXhr
		  übergeben wurden müssen (entweder anonym oder mit Namen)
		- success wird ausgeführt nachdem eine fehlerfreie Antwort nach 
		  Aufruf von ajaxSend eingetroffen ist
		- error wird ausgeführt wenn der HTTP-Status der Antwort weder 0 noch 200 ist
	*/
	function ajaxCreateXhr(success, error) {							
		let xhr = win.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
							
		// Anonyme Call-Back Funktion, die automatisch aufgerufen wird, wenn das XMLHttpRequest Objekt 
		// seinen Zustand ändert
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 0 || xhr.status == 200) {
					success(xhr.responseText);
				} else {
					error(xhr.responseText);
				}
			}
		};
						
		return xhr;
	};
					
	/*
		- Das von ajaxCreateXhr erzeugte XMLHttpRequest Objekt xhr wird benutzt, um data
		  mit der HTTP-Methode method (POST oder GET) an die Adresse url zu senden
		- data muss entweder ein String der Form key1=value1&key2=value2&... oder 
		  ein JavaScript key/value object {key1: value1, key2: value2, ...} sein
		- Sobald eine Antwort eintrifft, wird entweder die Call-Back Funktion success oder die 
		  Call-Back Funktion error ausgeführt (siehe ajaxCreateXhr)
	*/
	function ajaxSend(xhr, url, method, data) {		

		let params = '';

		if (typeof data != 'undefined') {
			params = typeof data == 'string' ? data : Object.keys(data).map(
					function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
				).join('&');
		}

		if (method == 'POST') {
			xhr.open(method, url);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send(params);
		} else if (method == 'GET') {
			if (params != '') {
				url += '?' + params;
			}
			xhr.open(method, url);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.send();
		} else {
			alert('Error: ajaxSend(...) must be called with either POST or GET, but not with ' + method);
		}
	};

	/////////////////////////////////////////////////////

	// Drag & Drop helpers

	// Konstruktor
	var DnD = function(obj, handle, pos, x, y) {	// Alternative Syntax zu function DnD(...) { ...
		if (!new.target) { 
			alert('Error: DnD(...) must be called with new'); 
			return; 
		}
		
		this.obj = obj;
		this.obj.style.position = pos;
		this.obj.style.left = x;
		this.obj.style.top = y;

		this.handle = handle;
		
		this.isGrabbed = false;
		
		this.offsetX = 0;
		this.offsetY = 0;
		
		// Registrieren der D&D Event Handler
		// bind(this) ist nötig, weil andernfalls 'this' in den Event Handlern 
		// zur Laufzeit *nicht* auf das zu bewegende Objekt zeigt
		this.handle.addEventListener('mousedown', this.grab.bind(this));
		
		// Der mouseup Event Handler könnte auch mit document oder this.obj registriert werden,
		// ohne die Funktion zu beeinträchtigen.
		win.addEventListener('mouseup', this.drop.bind(this)); 

		// Den mousemove Event Handler *muss* mit dem Fenster (oder dem Dokument) registriert
		// werden, weil andernfalls bei schnellen Bewegungen der Kontakt zwischen Maus und Objekt
		// verloren geht und das dragging stoppt.
		win.addEventListener('mousemove', this.drag.bind(this));
	};

	// Methoden
	// Objekt ergreifen
	DnD.prototype.grab = function(e) {
		this.offsetX = e.clientX - parseInt(this.obj.style.left);
		this.offsetY = e.clientY - parseInt(this.obj.style.top);
			
		this.isGrabbed = true;

		e.preventDefault();   // Erforderlich fuer Firefox/Chrome
	};

	// Objekt loslassen
	DnD.prototype.drop = function(e) {	
		this.isGrabbed = false;
	};

	// Objekt bewegen
	DnD.prototype.drag = function(e) {
		if (this.isGrabbed) {   
			this.obj.style.left = (e.clientX - this.offsetX).toString() + 'px';
			this.obj.style.top  = (e.clientY - this.offsetY).toString() + 'px';
		}	
	};

	/*
		Der Rückgabewert der anonymen Funktion ist ein JavaScript-Objekt { ... }.
		Es besteht aus Key/Value-Paaren, die so gestaltet sind, dass die Bibliotheksfunktionen
		in anderen JavaScripten aufgerufen und ausgeführt werden können, wenn die Bibliothek 
		inkludiert wurde, z.B. so: 
			var xhr = lib.ajaxCreateXhr(function() { ... }, function() { ... });
	*/
	return {
		getContext: getContext,
		clearCanvas: clearCanvas,
		drawLine: drawLine,
		drawArc: drawArc,
		writeText: writeText,
		ajaxCreateXhr: ajaxCreateXhr,
		ajaxSend: ajaxSend,
		DnD: DnD
	};
})(window);	