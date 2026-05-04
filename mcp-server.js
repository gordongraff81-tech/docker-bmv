const { Server } = require("@modelcontextprotocol/sdk/server/index.js");
const { StdioServerTransport } = require("@modelcontextprotocol/sdk/server/stdio.js");

const server = new Server(
  { name: "bmv-playwright", version: "1.0.0" },
  { capabilities: { tools: {} } }
);

async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
}

main();