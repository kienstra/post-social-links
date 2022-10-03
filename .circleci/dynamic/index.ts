import * as fs from "fs";
import CircleCI from "@circleci/circleci-config-sdk";

const config = new CircleCI.Config();
const workflow = new CircleCI.Workflow("test-lint");
config.addWorkflow(workflow);


const lint = new CircleCI.Job(
  "php-lint",
  new CircleCI.executors.DockerExecutor("cimg/php:8.0", "large"),
  [
    new CircleCI.commands.Checkout(),
    new CircleCI.commands.Run({
      command: "composer i && composer lint",
    }),
  ]
)
config.addJob(lint);
workflow.addJob(lint);

fs.writeFile("./dynamicConfig.yml", config.stringify(), () => {});
