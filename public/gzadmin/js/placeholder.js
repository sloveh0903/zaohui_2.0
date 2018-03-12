 // 让ie9以下 支持 placeholder属性
 $(function(){
   inputPlaceholder(document.getElementsByTagName("input"));
   textareaPlaceholder(document.getElementsByTagName("textarea") ) ;
 })
   
   //支持textarea
   function textareaPlaceholder(nodes,pcolor) {
      if(nodes.length && !("placeholder" in document.createElement("textarea"))){
          for(i=0;i<nodes.length;i++){
              var self = nodes[i],
                  placeholder = self.getAttribute('placeholder') || '';     
              self.onfocus = function(){
                  if(self.value == placeholder){
                     self.value = '';
                     self.style.color = "";
                  }               
              }
              self.onblur = function(){
                  if(self.value == ''){
                      self.value = placeholder;
                      self.style.color = pcolor;
                  }              
              }                                       
              self.value = placeholder;  
              self.style.color = pcolor;              
          }
      }
   }
   //支持input
   function inputPlaceholder(nodes,pcolor){
      if(nodes.length && !("placeholder" in document.createElement("input"))){
          for(i=0;i<nodes.length;i++){
              var self = nodes[i],
                  placeholder = self.getAttribute('placeholder') || '';     
              self.onfocus = function(){
                  if(self.value == placeholder){
                     self.value = '';
                     self.style.color = "";
                  }               
              }
              self.onblur = function(){
                  if(self.value == ''){
                      self.value = placeholder;
                      self.style.color = pcolor;
                  }              
              }                                       
              self.value = placeholder;  
              self.style.color = pcolor;              
          }
      }
   }