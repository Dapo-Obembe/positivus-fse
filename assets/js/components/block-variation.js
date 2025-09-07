wp.domReady(() => {
  wp.blocks.registerBlockVariation("core/group", {
    name: "section-with-container",
    title: "Section with Container",
    description:
      "A full-width outer section with a constrained inner container.",
    icon: "layout",

    // Attributes for the PARENT (outer) group block.
    attributes: {
      align: "full", // Makes the outer div full-width.
      tagName: "section",
      className: "is-variation-section-with-container",
      layout: {
        type: "constrained", // The align:'full' overrides this, but it's good practice.
      },
    },

    // This is the key: we are pre-defining the blocks INSIDE our variation.
    // It's an array of block "tuples": [ 'block-name', { attributes } ]
    innerBlocks: [
      [
        "core/group", // The block we want to insert inside.
        {
          // Attributes for the NESTED (inner) group block.
          className: "psv-container",
          layout: {
            type: "constrained",
          },
        },
      ],
    ],
    scope: ["inserter"],
  });
});
