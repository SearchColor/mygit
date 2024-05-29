//const logger = require("./logger");
var express = require('express');
var requestIp = require('request-ip');
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
  const hours = curtime.getHours();
  const min = curtime.getMinutes();
  const sec = curtime.getSeconds();
  var yy = year+"-"+month+"-"+date;
  var yyy = year+"-"+month+"-"+date+" "+hours+":"+min+":"+sec;
  
  
  const dd =  Date();
  const str = dd.toLocaleString();
  // console.log('Production Mode' );

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
router.get('/member', function (req, res) {/////////////////////////////////// Method : GET ///////////////////////////////////
    const q = req.query;
    var client_IP = requestIp.getClientIp(req);
    var Req_pa = JSON.stringify(q);
    if(q.Fcode =="O-M-003"){
      connection.query(`select *,CONVERT(AES_DECRYPT(phone,SHA2('hook',512)) using UTF8) as unhex_phone from tbl_member where m_idx=${q.m_idx} `, (error, rows, fields) => {
        if (error) throw error;
        console.log('Request params : ',Req_pa);
        
          if(rows.length == 0){
            let result = { Fcode: `${q.Fcode}`, REV: `2`}; 
            return res.json( result )
          }else{
            const data = `-------------------------------------------------------------------------------------------------------------\n`+
            `Get Req_params : ${Req_pa}\n select idx : ${q.m_idx}\n client IP : ${client_IP}\n`+str+
            "\n-------------------------------------------------------------------------------------------------------------\n";
            fs.appendFile('./log/users/'+yy+'.txt', data, (err)=>{ 
            if(err){ 
                console.log(err.message); 
            }
            }) 
            let result = { Fcode: `${q.Fcode}`, REV: `1` , Rows: rows}; 
            return res.json(result)
          }
      });
    }else{
      let result = { Fcode: `${q.Fcode}`, REV: `444`}; // Check the method or Fcode
      return res.json( result )
    }
    
  })
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
router.post('/member', function (req, res) {/////////////////////////////////// Method : POST /////////////////////////////////////////////////////////////
    console.log(req.body);
    var Fcode = req.body.Fcode;
    var client_IP = requestIp.getClientIp(req);
    var phone = req.body.phone;
    var NickName = req.body.NickName.split(' ').join('');
    var Req_body = JSON.stringify(req.body);
    var id = req.body.id;
    if(Fcode == "O-M-001"){
      
      //console.log("Fcode :",req.body.Fcode );

      if(phone.length != 11){
        console.log("phone length error" );
        let result = { Fcode: `${Fcode}`, REV: `4` };
      return res.json( result )
      }
      if(NickName === ''){
        console.log("NickName is null" );
        let result = { Fcode: `${Fcode}`, REV: `3` };
      return res.json( result )
      }

      connection.query(`select m_idx , count(m_idx) as count from tbl_member where id ='${id}'`, function (error, results, fields) {
        if (error) throw error;
          var dataCount = [];
          for (var data of results){
            dataCount.push(data.count);
          };
        if(dataCount == 0 ){

          connection.query(`insert into tbl_member(id,phone,NickName)values('${id}',AES_ENCRYPT('${phone}',SHA2('hook',512)),'${NickName}')`, (error, rows, fields) => {
            if (error) throw error;
              //console.log('result row : ', rows);
              console.log('Request body : ',Req_body);
            var insertId = rows.insertId;
            
            let result = { Fcode: `${Fcode}`, REV: `1` , m_idx: rows.insertId}; 

                const data = `-------------------------------------------------------------------------------------------------------------\n`+
                            `Post Req_body : ${Req_body}\n insertid : ${insertId}\n client IP : ${client_IP}\n`+str+ 
                            "\n-------------------------------------------------------------------------------------------------------------\n";
                fs.appendFile('./log/users/'+yy+'.txt', data, (err)=>{ 
                  if(err){ 
                      console.log(err.message); 
                  }
                }) 
            return res.json( result )
            });
        }else{
          console.log("This id is existed" , results[0].m_idx  );
          let result = { Fcode: `${Fcode}`, REV: `2` , m_idx: results[0].m_idx };
        return res.json( result )
        }
      });
    }else{
      let result = { Fcode: `${Fcode}`, REV: `444`}; // Check the method or Fcode
      return res.json( result )
    }
  });
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
router.put('/member', function (req, res) {  /////////////////////////////////// Method : PUT //////////////////////////////////////////////////////////////////////////////
    var Fcode = req.body.Fcode;
    var Req_body = JSON.stringify(req.body);
    var m_idx = req.body.m_idx;
    if(!!Number(m_idx)==false){
      console.log('type error : m_idx is not number');
      let result = { Fcode: `${Fcode}`, REV: `4`}; // Check the type of m_idx
      return res.json( result )
    }

    var Req_body = JSON.stringify(req.body);
    //var time =   timestamp('YYYY-MM-DD');
    var client_IP = requestIp.getClientIp(req);
    if(Fcode == "O-M-444"){  // update query
 
      connection.query(`update tbl_member set withdrawal ='1', withdrawalTime = '${yyy}' where m_idx = '${m_idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
        if(rows.affectedRows == 0){ // affectedrows 없는경우
          
          let result = { Fcode: `${Fcode}`, REV: `2`}; //update 해야할 affectedrows가 없는경우 (수정할 유저를 찾지못할경우)
          return res.json( result )
        }else if(rows.changedRows ==0){
          let result = { Fcode: `${Fcode}`, REV: `3`}; //update 한 rows가 없는경우 (이미 수정된경우)
          return res.json( result )
        }else{ // success

            console.log('results : ', rows.changedRows  );
            const data = `-------------------------------------------------------------------------------------------------------------\n`+
                            `Put Req_body : ${Req_body}\n put m_idx : ${m_idx}\n client IP : ${client_IP}\n`+str+ 
                            "\n-------------------------------------------------------------------------------------------------------------\n";
            fs.appendFile('./log/users/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 

          let result = { Fcode: `${Fcode}`, REV: `1`}; //success
          return res.json( result )
        }
        });
    }else if(Fcode == "O-M-005"){ // nickname update

          var NickName = req.body.NickName.split(' ').join('');
          if(NickName === ''){
            console.log("NickName is null" );
            let result = { Fcode: `${Fcode}`, REV: `3` };
          return res.json( result )
          }
          
          connection.query(`update tbl_member set NickName ='${NickName}'where m_idx = '${m_idx}' and withdrawal = 0`, function (error, results, fields) {
            if (error) throw error;
            console.log('Request body : ',Req_body);
            
              if(results.affectedRows == 0){ // affectedrows 없는경우
                let result = { Fcode: `${Fcode}`, REV: `2`}; //update 해야할 affectedrows가 없는경우 (수정할 유저를 찾지못할경우)
                return res.json( result )
              }
            let result = { Fcode: `${Fcode}`, REV: `1`}; //success
              return res.json( result )
          });

    }else if(Fcode == "O-M-006"){ // email update

          var email = req.body.email.split(' ').join('');
          if(email === ''){
            console.log("email is null" );
            let result = { Fcode: `${Fcode}`, REV: `3` };
          return res.json( result )
          }
          connection.query(`update tbl_member set email ='${email}'where m_idx = '${m_idx}' and withdrawal = 0`, function (error, results) {
            if (error) throw error;
            console.log('Request body : ',Req_body);
            
              if(results.affectedRows == 0){ // affectedrows 없는경우
                let result = { Fcode: `${Fcode}`, REV: `2`}; //update 해야할 affectedrows가 없는경우 (수정할 유저를 찾지못할경우)
                return res.json( result )
              }
            let result = { Fcode: `${Fcode}`, REV: `1`}; //success
              return res.json( result )
          });

    }
    else{
      //let result = 'Check the method or Fcode';
      let result = { Fcode: `${Fcode}`, REV: `444`}; // Check the method or Fcode
      return res.json( result )
    }
  })

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  router.delete('/member', function (req, res) {//////////////////////////////////////////////// Method : DELETE ////////////////////////////////////////////////
    var Fcode = req.body.Fcode;
    var m_idx = req.body.m_idx;
    var client_IP = requestIp.getClientIp(req);
    var Req_body = JSON.stringify(req.body);
    if(Fcode == "O-M-888"){
      connection.query(`delete from tbl_member where m_idx = '${m_idx}'`, (error, rows, fields) => {
        if (error) throw error;
          console.log('result row : ', rows);
          if(rows.affectedRows == 0){
            let result = { Fcode: `${Fcode}`, REV: `2`};
            return res.json( result )
          }else{
            let result = { Fcode: `${Fcode}`, REV: `1`};
            
            const data = `-------------------------------------------------------------------------------------------------------------\n`+
                         `Delete Req_body : ${Req_body}\n delete m_idx : ${m_idx}\n client IP : ${client_IP}\n`+str+
                         "\n-------------------------------------------------------------------------------------------------------------\n";
            fs.appendFile('./log/users/'+yy+'.txt', data, (err)=>{ 
              if(err){ 
                  console.log(err.message); 
              }
            }) 
          return res.json( result )
          }
      });
    }else{
      let result = 'Check the method or Fcode';
      return res.json( result )
    }
  })

module.exports = router;