

    <div class="col-md-12">
        <div class="content-panel">
            <h4><i class="fa fa-angle-right"></i> 图片新闻</h4><hr><table class="table table-striped table-advance table-hover">

                <thead>
                <tr>
                    <th><i class="fa fa-bullhorn"></i> 序号</th>
                    <th ><i class="fa fa-bookmark" ></i> 标题</th>
                    <th class="hidden-phone"><i class="fa fa-question-circle"></i> 添加时间</th>
                    <th><i class=" fa fa-edit"></i> 操作</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($answer_final as $key=>$value)
                    <tr >
                        <td class="hidden-phone">{{$key+1}}</td>
                        <td style="position:relative;">{{unCleanValue($value->title)}}</td>
                        <td class="hidden-phone">{{$value->addtime}}</td>
                        <td><a href="/manager/update_pic/{{$table}}/{{$value->id}}">修改</a>
                            <a href="/manager/delete/{{$table}}/{{$value->id}}" onclick= "javascript:return confirm('您确定要删除吗?')">删除</a>&nbsp;&nbsp;
                        </td>

                    </tr>
                    <tr>
                @endforeach

                </tbody>

            </table>
            {{$class->links()}}
            <div class="shadowL"></div>
            <div class="shadowR"></div>
        </div><!-- /content-panel -->
    </div>
    </body>



