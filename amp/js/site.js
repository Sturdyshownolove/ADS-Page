// Crtl+U
document.onkeydown = function(e) {
		if (e.ctrlKey && 
		(e.keyCode === 123 ||
		e.keyCode === 67 || 
		e.keyCode === 86 || 
		e.keyCode === 85 || 
		e.keyCode === 117)) {
		alert('KOPYALANAMAZ');
		return false;
		} else {
		return true;
		}
		};
//Sağ Tık		
document.addEventListener('contextmenu', event => event.preventDefault());