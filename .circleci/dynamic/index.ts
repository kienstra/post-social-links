import * as fs from "fs";
import CircleCI from "@circleci/circleci-config-sdk";

const config = new CircleCI.Config();
const workflow = new CircleCI.Workflow("test-lint");
config.addWorkflow(workflow);

[
  new CircleCI.Job(
    "php-lint",
    new CircleCI.executors.DockerExecutor("cimg/php:8.0", "large"),
    [
      new CircleCI.commands.Checkout(),
      new CircleCI.commands.Run({
        command: "composer i && composer lint",
      }),
    ]
  ),
  new CircleCI.Job(
    "zip",
    new CircleCI.executors.DockerExecutor("cimg/php:8.0"),
    [
      new CircleCI.commands.Checkout(),
      new CircleCI.commands.Run({ command: "composer zip" }),
      new CircleCI.commands.StoreArtifacts({
        path: "adapter-gravity-add-on.zip",
      }),
    ]
  ),
].forEach((job) => {
  config.addJob(job);
  workflow.addJob(job);
});

fs.writeFile("./dynamicConfig.yml", config.stringify(), () => {});
