/**
 * This JavaScript code registers a custom Gutenberg block called "Are You Paying Attention?" for use in WordPress. 
 * The block allows users to input two colors (sky color and grass color), which are stored as attributes. 
 * The `edit` function defines the block's interface in the WordPress editor, where users can input the sky and grass color values, 
 * which are stored as block attributes. The `save` function returns `null` because the block does not save static content to the database. 
 * Instead, the content is rendered dynamically on the front end using a PHP `render_callback` function in the index.php file.
 */

import "./index.scss";
import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker } from '@wordpress/components';
import { InspectorControls, BlockControls, AlignmentToolbar, useBlockProps } from "@wordpress/block-editor";
import { ChromePicker } from "react-color";

//
(function () {

  // 
  let locked = false;

  //
  wp.data.subscribe(function () {

    //
    const results = wp.data.select("core/block-editor").getBlocks().filter(function (block) {
      // 
      return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined;
    });

    // 
    if (results.length && locked == false) {
      locked = true; //
      wp.data.dispatch("core/editor").lockPostSaving("noanswer"); //
    }

    // 
    if (!results.length && locked) {
      locked = false; //
      wp.data.dispatch("core/editor").unlockPostSaving("noanswer"); //
    }
  });
})();


wp.blocks.registerBlockType("ourplugin/are-paying-attention", {
  title: "Are You Paying Attention?", // The title of the block shown in the Gutenberg block selector.
  icon: "smiley", // The block icon displayed in the block selector (WordPress Dashicons).
  category: "common", // Defines the block category where it will be listed in the Gutenberg editor.

  // Define the attributes for the block. These store user input.
  attributes: {
    question: { type: "string" }, //
    answers: { type: "array", default: [""] }, //
    correctAnswer: { type: "number", default: undefined }, //
    bgColor: { type: "string", default: "#EBEBEB" }, //
    theAlignment: { type: "string", default: "left" } //
  },
  // 
  description: "Give your audience a chance to prove their comprehension.",
  // 
  example: {
    attributes: {
      question: "What is my name?",
      correctAnswer: 3,
      answers: ["Meowsalot", "Barksalot", "Purrsloud", "Brad"],
      theAlignment: "center",
      bgColor: "#CFE8F1"
    }
  },
  // The edit function defines the block's interface and functionality in the Gutenberg editor.
  edit: EditComponent,

  // The save function is responsible for defining the block's output for the front-end.
  // Returning `null` means the block does not save any static HTML content in the database.
  // Instead, the block's content will be generated dynamically on the server-side using PHP.
  save: function () {
    return null; // No static HTML is saved; content is handled by the render callback in PHP.
  }
});


function EditComponent(props) {

  //
  // const blockProps = useBlockProps({
  //   className: "paying-attention-edit-block",
  //   style: { backgroundColor: props.attributes.bgColor }
  // });

  //
  function updateQuestion(value) {

    // 
    props.setAttributes({ question: value });
  }

  //
  function deleteAnswer(indexToDelete) {

    // 
    const newAnswers = props.attributes.answers.filter(function (x, index) {
      return index != indexToDelete;
    });

    // 
    props.setAttributes({ answers: newAnswers });

    //
    if (indexToDelete == props.attributes.correctAnswer) {
      props.setAttributes({ correctAnswer: undefined });
    }
  }


  //
  function markAsCorrect(index) {
    props.setAttributes({ correctAnswer: index });
  }

  // Return the block's UI in the editor, which includes two input fields for sky and grass color.
  return (

    // {...blockProps} -- 
    <div className="paying-attention-edit-block" style={{ backgroundColor: props.attributes.bgColor }}>

      {/*  */}
      <BlockControls>
        <AlignmentToolbar value={props.attributes.theAlignment} onChange={x => props.setAttributes({ theAlignment: x })} />
      </BlockControls>

      {/*  */}
      <InspectorControls>
        <PanelBody title="Background Color" initialOpen={true}>
          <PanelRow>
            {/* <ColorPicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({ bgColor: x.hex })} /> */}
            <ChromePicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({ bgColor: x.hex })} disableAlpha={true} />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <TextControl label="Question:" value={props.attributes.question} onChange={updateQuestion} style={{ fontSize: "20px" }} />
      <p style={{ fontSize: "13px", margin: "20px 0 8px 0" }}>Answers:</p>

      {/*  */}
      {props.attributes.answers.map(function (answer, index) {
        return (
          <Flex>
            <FlexBlock>
              {/* autoFocus={answer == undefined} --  */}
              <TextControl autoFocus={answer == undefined} value={answer} onChange={newValue => {
                const newAnswers = props.attributes.answers.concat([]); //
                newAnswers[index] = newValue; //
                props.setAttributes({ answers: newAnswers }); //
              }} />
            </FlexBlock>
            <FlexItem>
              <Button onClick={() => markAsCorrect(index)}>
                {/* icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} --  */}
                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} />
              </Button>
            </FlexItem>
            <FlexItem>
              <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
            </FlexItem>
          </Flex>
        );
      })}

      {/* {() => { props.setAttributes({ answers: props.attributes.answers.concat([undefined]) }); --  */}
      <Button isPrimary onClick={() => { props.setAttributes({ answers: props.attributes.answers.concat([undefined]) }); }}>Add another answer</Button>
    </div>
  );
}