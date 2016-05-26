function init(evt) {
    if ( window.svgDocument == null ) {
      svgDoc = evt.target.ownerDocument;
    }
}

function showName(evt) {
	svgDoc.getElementById('name').firstChild.data = evt.target.getAttribute("name");
}

function makeTransparent(evt) {
        evt.target.setAttributeNS(null,"opacity","0.5");
      }

function makeOpaque(evt) {
        evt.target.setAttributeNS(null,"opacity","1");
      }	  
	  

function Querystring(qs) { // optionally pass a querystring to parse
	this.params = {};
	this.get=Querystring_get;
	
	if (qs == null);
		qs=location.search.substring(1,location.search.length);

	if (qs.length == 0) 
		return;

	qs = qs.replace(/\+/g, ' ');
	var args = qs.split('&'); // parse out name/value pairs separated via &
	

	for (var i=0;i<args.length;i++) {
		var pair = args[i].split('=');
		var name = unescape(pair[0]);
		
		var value = (pair.length==2)
			? unescape(pair[1])
			: name;
		
		this.params[name] = value;
	}
}

function Querystring_get(key, default_) {
	var value=this.params[key];
	return (value!=null) ? value : default_;
}

function loadResults() {

	$.ajax({
	  url: '"../svg/provincias.php?source=" + source',
	  success: processResults( data ) {
		if (console && console.log){
		  console.log( 'Sample of data:', data.slice(0,100) );
		}
	  }
	});
}
	
function processResults(data) {

	if (data.success) {
		
		node = parseXML(data.content,document);
		node = node.getFirstChild();
		nodeList=node.getChildNodes();

		for (i=0; i <= node.getChildNodes().getLength() - 1; i++) {

			if (svgDoc.getElementById(nodeList.item(i).getAttribute("id")))
				svgDoc.getElementById(nodeList.item(i).getAttribute("id")).setAttribute("fill",nodeList.item(i).getAttribute("winner"));

		}
	}
	else {
		alert('problemas con recibo de resultado');
	}
}
