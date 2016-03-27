<?php
/**
 * Created by PhpStorm.
 * User: Tu
 * Date: 27/03/2015
 * Time: 11:42 SA
 */
class LPR_Question_Type_None extends LPR_Question_Type{
    function __construct( $type = null, $options = null ){
        parent::__construct( $type, $options );
    }

    function admin_script(){
        parent::admin_script();
        ?>
        <script type="text/html" id="tmpl-dropdown-question-answer">
            <tr>
                <td class="lpr-sortable-handle">
                    <i class="dashicons dashicons-sort"></i>
                </td>
                <td>
                    <input type="hidden" name="lpr_question[{{data.question_id}}][answer][is_true][__INDEX__]" value="0" />
                    <input type="radio" data-group="lpr-question-answer-{{data.question_id}}"  name="lpr_question[{{data.question_id}}][answer][is_true][__INDEX__]" value="1"  />

                </td>
                <td><input type="text" name="lpr_question[{{data.question_id}}][answer][text][__INDEX__]" value="" /></td>
                <td align="center"><span class=""><i class="dashicons dashicons-trash"></i></span> </td>
            </tr>
        </script>
    <?php
    }

    function admin_interface( $args = array() ){
        $uid = uniqid( 'lpr_question_answer' );
        $post_id = $this->get('ID');
        $this->admin_interface_head();
        $this->admin_interface_foot();
    }

    function save_post_action(){
        return false;
    }
}