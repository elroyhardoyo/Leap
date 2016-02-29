<?php
namespace Leap\View;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of Ajax
 *
 * @author User
 */
class Ajax extends Html {


    public function createSend ($buttonId, $formID, $errDivID, $log = 0)
    {
        $this->id = $buttonId;
        ?>
        <script type="text/javascript">
            $("<?=$buttonId;?>").onClick(
                function () {
                    $("<?=$formID;?>").send({
                        onSuccess: function () {
                            <? if($log){?>console.log(this.text);
                            <?}?>
                            if (this.text == 1) {
                                lwrefresh('<?=$_GET['parent_page'];?>');
                                lwclose(selected_page);
                            }
                            else {
                                $("<?=$errDivID;?>").html('<?=Lang::t('CrudSaveFailed');?>');
                            }
                        },
                        onFailure: function () {
                            $("<?=$errDivID;?>").html('<?=Lang::t('CrudSaveFailed');?>');
                            //console.log("Failed", this.text);
                        }
                    });
                });
        </script>
    <?
    }

}
