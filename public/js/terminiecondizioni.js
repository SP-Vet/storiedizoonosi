/*
 * Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC
 * Created on 2023
 * @author Eros Rivosecchi <e.rivosecchi@izsum.it>
 * @author IZSUM Sistema Informatico <sistemainformatico@izsum.it>
 * 
 * @license 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at

 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 * 
 * @version 1.0
 */

function apricondizioni() {
    $("#modalTerminicondizioni").modal('show');
}
var flagletto=0;
$(document).ready(function(){
    $('#presovisionetermini').click(function(){
        flagletto=1;
    });
    $('#terminiecondizioni').click(function(e){
        if(flagletto!==1){
            e.preventDefault();
            $("#modalTerminicondizioni").modal('show');
        }
    })
    $('#terminiecondizioni_en').click(function(e){
        if(flagletto!==1){
            e.preventDefault();
            $("#modalTerminicondizioni").modal('show');
        }
    })
})