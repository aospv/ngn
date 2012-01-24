<select class="selecta">
  <option>123 qweqwe qwe</option>
  <option>222222 eqwe</option>
  <option>33333332 32r23r32r 23r</option>
  <option>44444444</option>
  <option>5555555</option>
</select>
<script>
var selecta = new mooSelecta({
  selector: "select.selecta"
});
</script>


<style>

.cur {
_cursor: hand;
cursor: pointer;
}

.orange_title {
color: #f60;
font-weight: bold;
font-size: 14px;
display: inline;
float: left;
padding-bottom: 4px;
}

div.selectaTrigger {
height: 20px;
border-left: 1px solid #000;
padding-right: 30px;
padding-left: 5px;
padding-top: 5px;
overflow: hidden;
}

select.selecta {
height: 25px;
}

div.selectaWrapper {
border: 1px solid #000;
border-top: 0;
position: absolute;
z-index: 10000;
background: white;
overflow: hidden;
overflow-y: auto;
}

div.selectaOption {
padding-left: 8px;
padding-right: 8px;
padding-bottom: 2px;
padding-top: 2px;
border-bottom: 1px solid #eee;
clear: both;
}
div.selectaOptionSelected {
background: #ffffcf;
}
div.selectaDisabled {
background: #555;
color: #000;
}
div.selectaOptionOver {
background: yellow;
}

div.selecta2 {
font-weight: bold;
height: 18px;
padding-top: 3px;
padding-left: 4px;
padding-right: 20px;
overflow: hidden;
color: white;
}

div.selecta2Wrapper {
border: 1px solid #b6b7bf;
border-top: 0px;
padding: 2px;
background: #f2f2f2;
overflow: hidden;
overflow-y: auto;
position: absolute;
}

div.selecta2Option {
padding: 2px;
padding-left: 4px;
clear: both;
background: #fff;
color: #555;
font-weight: bold;
}

div.selecta2OptionSelected {
background: #666;
color: #ffffcf;

}
div.selecta2OptionOver {
background: #444;
color: #ffffcf;
}

</style>