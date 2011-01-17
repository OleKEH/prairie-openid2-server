// -----------------------------------------------------------------------
// This file is part of Prairie
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------


//puts browser window at top (out of frames) - stops bug with registering from inside hotmail frame.
if (self != top){
   if (document.images) top.location.replace(document.location.href);
   else top.location.href = document.location.href;
}

function objShowHide(id) {
	
	if (document.getElementById) {
		if (document.getElementById(id).style.display == 'block') {
			document.getElementById(id).style.display = 'none';
		}
		else {
			document.getElementById(id).style.display = 'block';
		}
	}
	else {
		if (document.layers) {
			if (document.id.visibility == 'block') {
				document.id.visibility = 'none';
			}
			else {
				document.id.visibility = 'block';
			}
		}
		else { // IE 4
			if (document.all.id.style.display == 'block') {
				document.all.id.style.display = 'none';
			}
			else {
				document.all.id.style.display = 'block';
			}
		}
	}
}