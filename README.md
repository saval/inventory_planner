# Inventory Planner coding test

# Installation:
  1. Download code

  2. Make sure you have installed Composer, Docker and Docker Compose

  3. Open the terminal, switch to the 'project' folder of downloaded application code and execute 'php composer.phar install'
  
  4. In the terminal go up one level ('cd ../' command) and execute 'docker-compose up -d'
  
  5. Application will be available at http://localhost:8080/index.php?config=process_conf_sample

# Configuration:
The process defined in the configuration file must be placed in the project/config folder. This file must contain a JSON file with the structure 

```
{ 
  "steps": [...], 
  "data" : [...] 
}
```

where the "steps" section describes the chain of actions that must be executed during the process, please note that actions order matters - they will be executed exactly in the same order they are defined in this section. The "data" section contains data needed for all steps, this is the usual key/value pairs.

Each step should be described with this data structure:

```
{
  "command": "Name of command",
  "params": { ... },
  "result": "name of variable to store result, optional"
}
```

The name of the action it's actually the name of the class that implements this action, this name should match the file and class names in the project/src/Command/ folder, the class structure described below. Action parameters are regular or associative array with variable names, these variables can be specified in the "data" section or be the result of previous steps - if the "result" section of the step defined than action's result will be stored in the variable with the specified name and it can be used on the next steps. An associative array with params is helpful when action uses params in the different goals, you can see it on the sample of Power action - for this action essential to know what is the base and what is exponent, so values available in the execution method by their names from the configuration. At the same time, for the Sum action, all parameters have the same importance this action just sum everything that is passed to it and thus better to pass parameters as a regular array.

You can prepare as many configuration files as you need, to execute a specific process you should put the name of the configuration file without extension to the 'config' parameter in the URL of executor script like shown in the installation instructions 'index.php?config=process_conf_sample'.

The sample of configuration implements the formula from the task description - **ax^2 + bx**.

# New actions adding
All actions must implement ICommand interface, to add new action just create a new file with a class implementing this interface in the project/src/Command/ folder and put the necessary logic to the execute() method. All parameters for this action will be automatically fetched from the storage and saved in the property $init_params. Let's see an example, assume we want to add new action 'SendEmail'. For that we should create a file project/src/Command/SendEmail with the next code:

```
namespace Process\Command;

class SendEmail extends SimpleCommand implements ICommand
{
    public function execute(): int
    {
        //here is the code to send an email
    }
}
```
