// s.pageName = s_properties.pageName;
// s.channel = s_properties.channel;
// s.prop1 = s_properties.prop1;
// s.prop2 = s_properties.prop2;


for(var key in s_properties) {
  if(s_properties.hasOwnProperty(key)) { //to be safe
    s[key] = s_properties[key];
  }
}


/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
var s_code=s.t();if(s_code)document.write(s_code)//-->
