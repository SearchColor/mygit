const logger = require("./logger");
var express = require('express');
var router = express.Router();


// Use bcryptjs module 
//const bcrypt = require("bcryptjs");

const fs = require('fs')

const mysql = require('mysql');
const connection = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root',
  password: '54ww4543q@',
  database: 'Modu'
});
  router.use(express.json()); 
  router.use(express.urlencoded({ extended: true }));


  let curtime = new Date();
  const year = curtime.getFullYear();
  const date = curtime.getDate();
  const month = curtime.getMonth()+1;
  var yy = year+"-"+month+"-"+date;
  
  const dd =  Date();
  const str = dd.toLocaleString();
  console.log('Production Mode' );

  // middleware that is specific to this router
  router.use(function timeLog(req, res, next) {
    
    console.log('Time: ', str );
        // const data = "get data"+str+"\n";
        // fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
        //   if(err){ 
        //       console.log(err.message); 
        //   }
        // }) 
        
    next();
  });

  router.post('/member', function (req, res) {
    console.log(req.body);
    var Fcode = req.body.Fcode;
    if(Fcode == "L-001"){
      var NickName = req.body.NickName;
      
      console.log("Fcode :",req.body.Fcode );
      
      connection.query(`insert into tbl_member(NickName)values('${NickName}')`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
        //return res.json({ rows })
        var insertId = rows.insertId;
        let result = { Fcode: `${Fcode}`, REV: `1` , useridx: rows.insertId}; 

            const data = `post insertid : ${insertId}\n`+str+"\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
        return res.json( result )
      });
    }
  });

  router.put('/member', function (req, res) {  // Method : PUT 
    var Fcode = req.body.Fcode;  
    if(Fcode == "L-002"){  // update query
      var id = req.body.id;
      var idx = req.body.idx;
      connection.query(`update test set id ='${id}' where idx = '${idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
        //return res.json({ rows })
        if(rows.affectedRows == 0){ // affectedrows 없는경우
          
          let result = { Fcode: `${Fcode}`, REV: `2`};
          return res.json( result )
        }else{ // success

            console.log('Time: ', str );
            const data = "put data"+str+"\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 

          let result = { Fcode: `${Fcode}`, REV: `1`};
          return res.json( result )
        }
        
      });
    }
  })

  router.delete('/member', function (req, res) {
    var Fcode = req.body.Fcode;
    if(Fcode == "L-444"){
      var idx = req.body.idx;
      connection.query(`delete from test where idx = '${idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
        //return res.json({ rows })
        let result = { Fcode: `${Fcode}`, REV: `1`};
            console.log('Time: ', str );
            const data = "delete data"+str+"\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
        return res.json( result )
      });
    }
  })

  //select for name
  router.get('/member/:name', function (req, res) {
    const q = req.params
    connection.query(`SELECT * from member where name = '${q.name}' and withdrawal = '0'`, (error, rows, fields) => {
      if (error) throw error;
      if(!rows.length == 0){
        console.log('Time: ', str );
        console.log('User  : ', rows.length);  
            
            const data = "delete data"+str+"\n";
            fs.appendFile('./log/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
      return res.status(200).json({ rows })
      }else{
        console.log('rows : null');  
      return res.status(403).json({ rows })
      }
    });
  })
  //select for useridx
  router.get('/useridx/:useridx', function (req, res) {
    const q = req.params
    console.log(q.useridx);
    connection.query(`SELECT * from member where useridx = '${q.useridx}' and withdrawal = '0' `, (error, rows, fields) => {
      if (error) throw error;
      console.log('User info is: ', rows);
      return res.json({ rows })
    });
  })

  router.get('/password/:password', function (req, res) {
    const q = req.params
    console.log(q.useridx);
    connection.query(`SELECT * from member where password = '${q.password}' `, (error, rows, fields) => {
      if (error) throw error;
      console.log('User info is: ', rows);
      return res.json({ rows })
    });
  })


  

module.exports = router;