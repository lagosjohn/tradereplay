function helexGrid(header_obj,data_obj)
{
  this.tbl = document.getElementById(data_obj);
  this.hdrtbl = document.getElementById(header_obj);
  this.markerHTML = "|";
  this.minWidth = 10;
  this.dragingColumn = null;
  this.startingX = 0;
  this.currentX = 0;

this.addRowToTable = function (data)
{
  //var tbl = document.getElementById('mp_trades');
  var lastRow = this.tbl.rows.length;
  var cell;
  var textNode;
  // if there's no header row in the table, then iteration = lastRow + 1
  var iteration = lastRow;
  var row = this.tbl.insertRow(lastRow);
//  row.setAttribute("class",((lastRow%2) ?"trade_even" :"trade_uneven"));
  row.setAttribute( "id", lastRow );
  row.setAttribute("onclick","tradesClick("+lastRow+")");
  
  for(var i = 0; i < data.length; i++) {
  		cell = row.insertCell(i);
 	 	textNode = document.createTextNode(data[i]);
  		cell.appendChild(textNode);
		textNode.size = 30;
		}
  /* 
  // select cell
  var cellRightSel = row.insertCell(2);
  var sel = document.createElement('select');
  sel.name = 'selRow' + iteration;
  sel.options[0] = new Option('text zero', 'value0');
  sel.options[1] = new Option('text one', 'value1');
  cellRightSel.appendChild(sel);
  */
}


        

this.getNewWidth=function  () {
            var newWidth = minWidth;
            if (this.dragingColumn != null) {
                newWidth = parseInt (this.dragingColumn.style.width); //parentNode
                if (isNaN (newWidth)) {
                    newWidth = 0;
                }
                newWidth += this.currentX - this.startingX;
                if (newWidth < this.minWidth) {
                    newWidth = this.minWidth;
                }
            }
            return newWidth;
        }

this.ColumnGrabberMouseUp=function ()		{
			 if (this.dragingColumn != null) {
				this.dragingColumn.inDrag = false;
				this.dragingColumn.style.width = this.savedWidth;
				this.dragingColumn.style.marginLeft = this.savedMarginLeft;
				this.dragingColumn.style.backgroundPosition = this.savedBackgroundPosition;
				this.dragingColumn.style.left = this.savedLeft;
				this.UpdateColumns(true);
				
				}
			return true;
			}
		
this.UpdateColumns=function (fullUpdate){
		/*	var total = 0;
			for (var i=0; i < kColumns.length - 1; i++){
				var width = Math.round(currentColumnWidths[i] * 100);
				total += width;
				columnHeaderCells[i].style.width = width + "%";
				if (fullUpdate) 
					columnBodyCells[i].style.width = width + "%";
				}
			columnHeaderCells[kColumns.length - 1].style.width = (100 - total) + "%";
			if (fullUpdate)  
				columnBodyCells[kColumns.length - 1].style.width = (100 - total) + "%";
				*/
			}
			
this.columnMouseDown = function  (event) {
		
            if (!event) {
                event = window.event;
            }
            if (this.dragingColumn != null) {
                this.ColumnGrabberMouseUp ();
            }
            this.startingX = event.clientX;
            this.currentX = startingX;
            this.dragingColumn = this;

            return true;
        }

this.columnMouseUp = function  () {
            if (this.dragingColumn != null) {
			//alert(dragingColumn.style.width+"=="+dragingColumn.parentNode.style.width);
                this.dragingColumn.style.width = getNewWidth ()+"px";
				this.resizeTable ("mp_trades",this.dragingColumn.idx,this.dragingColumn.style.width);
				//dragingColumn.offsetWidth=getNewWidth ();
                this.dragingColumn = null;
				
            }
			return true;
        }

this.columnMouseMove = function  (event) {
		
            if (!event) {
                event = window.event;
            }
            if (this.dragingColumn != null) {
			    this.currentX = event.clientX;
				//dragingColumn.offsetWidth=getNewWidth ();
                this.dragingColumn.style.width = getNewWidth ()+"px";
                this.startingX = event.clientX;
				
                this.currentX = this.startingX;
			
			}
			return true;
        }

this.installTable = function  () {
            var table = this.hdrtbl; //document.getElementById (tableId);
            // Test if there is such element in the document
            if (table != null) {
                // Test is this element a table
                if (table.nodeName.toUpperCase () == "TABLE") {
                    document.body.onmouseup = this.columnMouseUp;
                    document.body.onmousemove = this.columnMouseMove;
                    for (i = 0; i < table.childNodes.length; i++) {
                        var tableHead = table.childNodes[i];
                        // Look for the header
                        // Tables without header will not be handled.
                        if (tableHead.nodeName.toUpperCase () == "THEAD") {
                            // Go through THEAD nodes and set resize markers
                            // IE in THEAD contains TR element which contains TH elements
                            // Mozilla in THEAD contains TH elements
                            for (j = 0; j < tableHead.childNodes.length; j++) {
                                var tableHeadNode = tableHead.childNodes[j];
                                // Handles IE style THEAD with TR
                                if (tableHeadNode.nodeName.toUpperCase () == "TR") {
								
                                    for (idx=0,k = 0; k < tableHeadNode.childNodes.length; k++) {
									//alert(tableHeadNode.childNodes[k].nodeName);
									if (tableHeadNode.childNodes[k].nodeName.toUpperCase () == "TD"){
                                        var column = tableHeadNode.childNodes[k];
										//column.onmousedown = columnMouseDown;
										//alert(column.toSource());
										
                                        var marker = document.createElement ("span");
                                       // column.innerHTML = markerHTML;
                                        //marker.style.cursor = "move";
                                        column.onmousedown = this.columnMouseDown;
                                        //column.appendChild (marker);
										
                                        if (column.offsetWidth < this.minWidth) {
                                            column.style.width = this.minWidth+"px";
											//alert(column.offsetWidth+"=="+minWidth+"=="+column.style.width);
                                        }
                                        else {
                                              column.style.width = column.offsetWidth+"px";
											//alert(column.offsetWidth+"=="+minWidth+"=="+column.style.width);
                                        }
										column.idx=idx++;
										}
                                    }
                                }
                                // Handles Mozilla style THEAD
                                else if (tableHeadNode.nodeName.toUpperCase () == "TH") {
                                    var column = tableHeadNode;
                                    var marker = document.createElement ("span");
                                    marker.innerHTML = markerHTML;
                                    marker.style.cursor = "move";
                                    marker.onmousedown = this.columnMouseDown;
                                    column.appendChild (marker);
                                    if (column.offsetWidth < this.minWidth) {
                                        column.style.width = this.minWidth;
                                    }
                                    else {
                                        column.style.width = column.offsetWidth;
                                    }
									
                                }
                            }
                            table.style.tableLayout = "fixed";
                            // Once we have found THEAD element and updated it
                            // there is no need to go through rest of the table
                            i = table.childNodes.length;
                        }
                    }
                }
            }
        }


this.resizeTable = function  (idx,xWidth) {
            var table = this.tbl;
            // Test if there is such element in the document
            if (table != null) {
                // Test is this element a table
                if (table.nodeName.toUpperCase () == "TABLE") {
                    
                    for (i = 0; i < table.childNodes.length; i++) {
                        var tableHead = table.childNodes[i];
                        // Look for the header
                        // Tables without header will not be handled.
                        if (tableHead.nodeName.toUpperCase () == "THEAD") {
                            // Go through THEAD nodes and set resize markers
                            // IE in THEAD contains TR element which contains TH elements
                            // Mozilla in THEAD contains TH elements
                            for (j = 0; j < tableHead.childNodes.length; j++) {
                                var tableHeadNode = tableHead.childNodes[j];
                                // Handles IE style THEAD with TR
                                if (tableHeadNode.nodeName.toUpperCase () == "TR") {
								
                                    for (x=0,k = 0; k < tableHeadNode.childNodes.length; k++) {
									//alert(tableHeadNode.childNodes[k].nodeName);
									if (tableHeadNode.childNodes[k].nodeName.toUpperCase () == "TH" ){
									if (x==idx) {
									//alert( k+"=="+idx);
                                        var column = tableHeadNode.childNodes[k];
										//alert(tableId+"=="+idx+"=="+xWidth);                                       										
                                        column.style.width = this.xWidth;
										return;
										}
										x++;
										}
                                    }
                                }
                                // Handles Mozilla style THEAD
                                else if (tableHeadNode.nodeName.toUpperCase () == "TH") {
                                    var column = tableHeadNode;
                                    var marker = document.createElement ("span");
                                    marker.innerHTML = markerHTML;
                                    marker.style.cursor = "move";
                                    marker.onmousedown = columnMouseDown;
                                    column.appendChild (marker);
                                    if (column.offsetWidth < this.minWidth) {
                                        column.style.width = this.minWidth;
                                    }
                                    else {
                                        column.style.width = column.offsetWidth;
                                    }
                                }
                            }
                            table.style.tableLayout = "fixed";
                            // Once we have found THEAD element and updated it
                            // there is no need to go through rest of the table
                            i = table.childNodes.length;
                        }
                    }
                }
            }
        }
}