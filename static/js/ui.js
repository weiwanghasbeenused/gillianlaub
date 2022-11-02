var body = document.body;
var sCat_toggle_btn;
// category toggle
if( sCat_toggle_btn = document.getElementById('cat-toggle-btn'))
{
    sCat_toggle_btn.onclick = function(){
        if(body.getAttribute('category') == 'projects')
            body.setAttribute('category', 'commissions');
        else
            body.setAttribute('category', 'projects');
    };
}