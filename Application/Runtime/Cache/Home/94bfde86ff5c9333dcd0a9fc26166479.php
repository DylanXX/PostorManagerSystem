<?php if (!defined('THINK_PATH')) exit();?>                    <h1 class="text-light">未收件快递表 <span class="mif-table place-right"></span></h1>
                    <hr class="thin bg-grayLighter">
                    <button class="button primary" onclick="selectCheckBox('true')"><span class="mif-plus"></span> 选择全部</button>
                    <button class="button success" onclick="selectCheckBox('false')"><span class="mif-play"></span> 全部取消</button>
                    <button class="button warning" onclick="delayOrders()"><span class="mif-loop2 ani-spin"></span> 选择项目延期</button>
                    <hr class="thin bg-grayLighter">
                    <table class="dataTable border bordered" >
                        <thead>
                        	<tr>
                           				 <td style="width: 20px">
                           				 </td>
										<td class="sortable-column" style="width: 100px">订单号</td>
										<td class="sortable-column">录入时间</td>
										<td class="sortable-column">库存位置</td>
										<td class="sortable-column">订单信息</td>
										<td class="sortable-column" >邮递员编号</td>
							</tr>
                        </thead>
                        <tbody  id = "OrderTable">
                        	
                        </tbody>
                    </table>
                    <div ></div>