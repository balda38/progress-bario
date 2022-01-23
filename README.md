# ProgressBario
It's simple progress bar for your php CLI tasks.

## Requirements
You need **PHP 5.3 or higher** and...that it is all :)

## Install
1. Add to your `composer.json` those lines at `"repositories"`:
```
    {
        "type": "vcs",
        "url": "https://github.com/balda38/progress-bario.git"
    }
```
2. Run `composer require balda38/progress-bario:dev-master`.

## Arguments
You can use ProgressBario with some arguments:

| # | Type   |Name            | Description                                                | Required | Default      |
|---|------  |----------------|------------------------------------------------------------|----------|--------------|
| 1 | int    |total           | Total number of iterations which will be processed         | true     | None         |
| 2 | string |taskName        | Your task custom name                                      | false    | Empty string |
| 3 | bool   |showTime        | Show remaining time on process and total time after finish | false    | false        |
| 4 | bool   |showMemoryUsage | Show used memory and peak memory usage after finish        | false    | false        |

## Usage
1. init your ProgressBario instance;
2. use ProgressBario `makeStep()` function on every iteration of your `for`, `foreach` or `while` construction;
3. use ProgressBario `close()` function after ending your `for`, `foreach`, `while` constructions or where you want (for example in `catch`).

Do like me:
```
$countOfProcess = 1000;
$progressBario = new Balda38\ProgressBario($countOfProcess);
for ($i = 0; $i < $countOfProcess; ++$i) {
    sleep(1); // do what you want
    $progressBario->nextStep();
}
$progressBario->close();
```
