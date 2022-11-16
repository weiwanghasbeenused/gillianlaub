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
var sCat_name = document.getElementsByClassName('cat-name');
if(sCat_name.length != 0)
{
    [].forEach.call(sCat_name, function(el, i){
        let catName = el.id.replace('cat-', '');
        el.addEventListener('click', function(){
            body.setAttribute('category', catName);
        });
    });
}