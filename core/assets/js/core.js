if(void 0===Joomla)var Joomla={};function writeDynaList(e,t,o,a,n){var r="\n\t<select "+e+">",i=0;for(x in t){if(t[x][0]==o){var s="";(a==o&&n==t[x][1]||0==i&&a!=o)&&(s='selected="selected"'),r+='\n\t\t<option value="'+t[x][1]+'" '+s+">"+t[x][2]+"</option>"}i++}r+="\n\t</select>",document.writeln(r)}function changeDynaList(e,t,o,a,n){var r=document.adminForm[e];for(i in r.options.length)r.options[i]=null;for(x in i=0,t)t[x][0]==o&&(opt=new Option,opt.value=t[x][1],opt.text=t[x][2],(a==o&&n==opt.value||0==i)&&(opt.selected=!0),r.options[i++]=opt);r.length=i}function radioGetCheckedValue(e){if(!e)return"";var t=e.length;if(null==t)return e.checked?e.value:"";for(var o=0;o<t;o++)if(e[o].checked)return e[o].value;return""}function getSelectedValue(e,t){var o=document[e][t];return i=o.selectedIndex,null!=i&&-1<i?o.options[i].value:null}function checkAll(e,t){if(t||(t="cb"),e.form){for(var o=0,a=0,n=e.form.elements.length;a<n;a++){var r=e.form.elements[a];r.type==e.type&&(t&&0==r.id.indexOf(t)||!t)&&(r.checked=e.checked,o+=1==r.checked?1:0)}return e.form.boxchecked&&(e.form.boxchecked.value=o),!0}var i=document.adminForm,s=(o=i.toggle.checked,n=e,0);for(a=0;a<n;a++){var l=i[t+""+a];l&&(l.checked=o,s++)}document.adminForm.boxchecked.value=o?s:0}function listItemTask(e,t){return Joomla.listItemTask(e,t)}function isChecked(e){1==e?document.adminForm.boxchecked.value++:document.adminForm.boxchecked.value--}function submitbutton(e){return Joomla.submitbutton(e)}function submitform(e){return Joomla.submitform(e)}function popupWindow(e,t,o,a,n){return Joomla.popupWindow(e,t,o,a,n)}function tableOrdering(e,t,o){var a=document.adminForm;return Joomla.tableOrdering(e,t,o,a)}function saveorder(e,t){checkAll_button(e,t)}function checkAll_button(e,t){return Joomla.saveOrder(e,t)}Joomla.editors={},Joomla.editors.instances={},Joomla.submitform=function(e,t){var o;void 0===t?(t=document.getElementById("adminForm"))||(t=document.adminForm):t instanceof jQuery&&(t=t[0]),void 0!==e&&""!==e&&(t.task.value=e),document.createEvent?(o=document.createEvent("HTMLEvents")).initEvent("submit",!0,!0):document.createEventObject&&((o=document.createEventObject()).eventType="submit"),"function"==typeof t.onsubmit?t.onsubmit():"function"==typeof t.dispatchEvent?t.dispatchEvent(o):"function"==typeof t.fireEvent&&t.fireEvent(o),t.submit()},Joomla.submitbutton=function(e){Joomla.submitform(e)},Joomla.JText={strings:{},_:function(e,t){return void 0!==this.strings[e.toUpperCase()]?this.strings[e.toUpperCase()]:t},load:function(e){for(var t in e)this.strings[t.toUpperCase()]=e[t];return this}},Joomla.replaceTokens=function(e){for(var t=document.getElementsByTagName("input"),o=0;o<t.length;o++)"hidden"==t[o].type&&32==t[o].name.length&&"1"==t[o].value&&(t[o].name=e)},Joomla.isEmail=function(e){return new RegExp("^[\\w-_.]*[\\w-_.]@[\\w].+[\\w]+[\\w]$").test(e)},Joomla.checkAll=function(e,t){if(t||(t="cb"),e.form){for(var o=0,a=0,n=e.form.elements.length;a<n;a++){var r=e.form.elements[a];r.type==e.type&&(t&&0==r.id.indexOf(t)||!t)&&(r.checked=e.checked,o+=1==r.checked?1:0)}return e.form.boxchecked&&(e.form.boxchecked.value=o),!0}return!1},Joomla.renderMessages=function(e){Joomla.removeMessages();var t=$("#system-message-container"),n=$("<dl>").attr("id","system-message").attr("role","alert");$.each(e,function(e,t){$("<dt>").addClass(e).html(e).appendTo(n);var o=$("<dd>").addClass(e).addClass("message"),a=$("<ul>");$.each(t,function(e,t,o){$("<li>").html(t).appendTo(a)}),a.appendTo(o),o.appendTo(n)}),n.appendTo(t),$(document).trigger("renderMessages")},Joomla.removeMessages=function(){$("#system-message-container > *").remove()},Joomla.isChecked=function(e,t){void 0===t&&((t=document.getElementById("adminForm"))||(t=document.adminForm)),1==e?t.boxchecked.value++:t.boxchecked.value--},Joomla.popupWindow=function(e,t,o,a,n){var r=(screen.width-o)/2,i="height="+a+",width="+o+",top="+(screen.height-a)/2+",left="+r+",scrollbars="+n+",resizable";window.open(e,t,i).window.focus()},Joomla.tableOrdering=function(e,t,o,a){void 0===a&&((a=document.getElementById("adminForm"))||(a=document.adminForm)),a.filter_order.value=e,a.filter_order_Dir.value=t,Joomla.submitform(o,a)},Joomla.listItemTask=function(e,t){var o=document.adminForm,a=o[e];if(a){for(var n=0;;n++){var r=o["cb"+n];if(!r)break;r.checked=!1}a.checked=!0,o.boxchecked.value=1,Joomla.submitbutton(t)}return!1},Joomla.saveOrder=function(e,t){t||(t="saveorder");for(var o=0;o<=e;o++){var a=document.adminForm["cb"+o];if(!a)return void alert("You cannot change the order of items, as an item in the list is `Checked Out`");0==a.checked&&(a.checked=!0)}return Joomla.submitform(t)},Joomla.hasClass=function(e,t){return e.classList?e.classList.contains(t):new RegExp("\\b"+t+"\\b").test(e.className)},Joomla.addClass=function(e,t){e.classList?e.classList.add(t):hasClass(e,t)||(e.className+=" "+t)},Joomla.removeClass=function(e,t){e.classList?e.classList.remove(t):e.className=e.className.replace(new RegExp("\\b"+t+"\\b","g"),"")},document.addEventListener("DOMContentLoaded",function(){var e,t=document.getElementsByClassName("toolbar");for(e=0;e<t.length;e++)t[e].addEventListener("click",function(e){e.preventDefault();var t=this;if(Joomla.hasClass(t,"toolbar-submit")&&(Joomla.hasClass(t,"toolbar-list")&&0==document.adminForm.boxchecked.value?alert(t.getAttribute("data-message")):t.getAttribute("data-task")?Joomla.submitbutton(t.getAttribute("data-task")):console.log("Error: no task found.")),Joomla.hasClass(t,"toolbar-popup")){var o=t.getAttribute("data-width")?t.getAttribute("data-width"):700,a=t.getAttribute("data-height")?t.getAttribute("data-height"):500;Joomla.popupWindow(t.getAttribute("href"),t.getAttribute("data-message"),o,a,1)}Joomla.hasClass(t,"toolbar-confirm")&&(Joomla.hasClass(t,"toolbar-list")&&0==document.adminForm.boxchecked.value?alert(t.getAttribute("data-message")):confirm(t.getAttribute("data-confirm"))&&(t.getAttribute("data-task")?Joomla.submitbutton(t.getAttribute("data-task")):console.log("Error: no task found.")))});var o=document.getElementsByClassName("checkbox-toggle");for(e=0;e<o.length;e++)o[e].addEventListener("click",function(e){Joomla.hasClass(this,"toggle-all")?Joomla.checkAll(this):Joomla.isChecked(this.checked)});var a=document.getElementsByClassName("filter-submit");for(e=0;e<a.length;e++)a[e].addEventListener("change",function(e){this.form.submit()});var n=document.getElementsByClassName("filter-clear");for(e=0;e<n.length;e++)n[e].addEventListener("click",function(e){var t,o=this.form.getElementsByClassName("filter");for(t=0;t<o.length;t++)"select"==o[t].tagName.toLowerCase()&&(o[t].selectedIndex=0),"input"==o[t].tagName.toLowerCase()&&(o[t].value="");this.form.submit()});n=document.getElementsByClassName("grid-order");for(e=0;e<n.length;e++)n[e].addEventListener("click",function(e){return e.preventDefault(),Joomla.tableOrdering(this.getAttribute("data-order"),this.getAttribute("data-direction"),this.getAttribute("data-task")),!1});n=document.getElementsByClassName("grid-order-save");for(e=0;e<n.length;e++)n[e].addEventListener("click",function(e){e.preventDefault();var t=this.getAttribute("data-rows"),o=this.getAttribute("data-task");return t&&o&&Joomla.saveOrder(t,o),!1});n=document.getElementsByClassName("grid-action");for(e=0;e<n.length;e++)n[e].addEventListener("click",function(e){e.preventDefault();var t=this.getAttribute("data-id"),o=this.getAttribute("data-task");return t&&o&&Joomla.listItemTask(t,o),!1})});
