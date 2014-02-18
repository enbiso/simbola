function getNodeData(node){
    var selNodes = [];                
    if(node.hasChildren()){
        node.visit(function(cnode){                        
            selNodes.push({
                'state':cnode.isSelected()?'GRANT':'REVOKE',
                'key':cnode.data.key
            });                        
        });  
    }else{ 
        selNodes.push({
            'state':node.isSelected()?'GRANT':'REVOKE',
            'key':node.data.key
        }); 
    }      
    return selNodes;
}