function Mapa(map){
    this.map = map;
    var self = this;
    
    //Startování mapy, [x,y] výchozí pozice
    this.init = function(x,y){
        
    };
    
    //Mapa se vycentruje na pole [x,y]
    this.map.pozice = function(x,y){
        
    };
    
    //Bloky která se mají překreslit
    //bloky - seznam bloků [[x1,y1],[x2,y2],...,[xn,yn]]
    this.map.obnovit = function(bloky){
        
    };
    
    //Vykreslení cesty
    //počátek - pole [x,y]
    //cesta - seznam polí [[x1,y1],[x2,y2],...,[xn,yn]]
    this.map.renderCesta = function(pocatek,cesta){
        
    };
    
    //funkce
    //arg1 - seznam bloků, které chci načíst
    //arg2 - callback, provede se na každém bloku

    this.map.load([[0,0],[1,1]],function(json,x,y){
        //json - seznam políček v bloku, seřazené zleva od vrchu,
        //       každé políčko je seznam a obsahuje:    0 - souřadnice x
        //                                              1 - souřadnice y
        //                                              2 - typ: 0 - volné pole
        //                                                       1 - město
        //                                                       2 - les
        //                                                       3 - kopec
        //                                                       4 - voda
        //                                              
        //                                              3 - id
        //                                              4 - orientace políčka
        //                                              5 - id státu
        //                                              6 - hranice
        //                                              pokud je typ město, pak obsahuje navíc:
        //                                              7 - populace
        //                                              8 - jméno města
        //                                              9 - jméno hráče
        //[x,y] - souřadnice bloku                
    });
    
    //získání jednoho pole
    this.map.getPole(5,5);
    
    //získání jména státu
    //arg1 - id státu
    this.map.getStat(5);
    
    //id aktuálního města
    this.map.game.mesto.id;
    
    //id aktuálního státu
    this.map.game.stat;
    
    //zobrazení náhledu aktivního města
    this.map.game.page_go("mesto");
    
    //zobrazení políčka s id 5
    this.map.game.page_go("mestoinfo/5");
}