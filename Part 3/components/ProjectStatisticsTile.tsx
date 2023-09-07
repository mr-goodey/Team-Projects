// import { getSampleEmployeeData, getSampleTaskData, getSampleProjectData } from "@/pages/analysis";
import {
  Card,
  Title,
  DonutChart,
  Legend,
  BarChart,
  Color,
  Flex,
  Text,
  ProgressBar,
} from "@tremor/react";
import { useEffect, useState } from "react";
import {
  getTasksByApi,
  getEmployeesByApi,
  getProjectsByApi,
} from "@/pages/analysis";

// OVERVIEW: Calculates data for and creates the card containing a pie chart and bar graph

// Draws the elements to the screen
// Takes: data for the progress bar, pie chart and bar chart as input
export function ProjectStats(props: any) {
  console.log(props.kanbanPieData);
  return (
    <div className="card flex flex-col shadow-md text-base-content bg-base-300 p-4 m-4 w-90% h-1/2">
      <Title className="mb-2 text-2xl text-base-content">Statistics</Title>
      {props.currentProject == 0 ? (
        <div className="flex h-full justify-center items-center mb-8">
          <div>
            <Text className="text-2xl text-base-content">
              Please select a project to view statistics
            </Text>
          </div>
        </div>
      ) : (
        <>
          <progress
            className="my-4 p-2 progress progress-success"
            value={props.progressBarData}
            max="100"
          ></progress>

          <div className="flex flex-row">
            <div className="w-1/2">
              <Title className=" mb-2 text-xl text-base-content">
                Task data:
              </Title>
              <DonutChart
                data={props.kanbanPieData}
                category="value"
                index="name"
                variant="pie"
                colors={["rose", "orange", "amber", "lime", "indigo"]}
                style={{ height: "300px" }} // Adjust the height as needed
              />
            </div>
            <div className="w-1/2">
              <Title className=" mb-2 text-xl text-base-content">
                Employee data:
              </Title>
              <BarChart
                layout="horizontal"
                data={props.taskBarData}
                index="employee"
                categories={["tasks"]}
                showAnimation={true}
                showLegend={false}
                colors={[
                  "indigo",
                  "fuchsia",
                  "amber",
                  "yellow",
                  "teal",
                  "emerald",
                ]}
                style={{ height: "300px" }} // Adjust the height as needed
              />
            </div>
          </div>
        </>
      )}
    </div>
  );
}

// Gathers and formats data used to display the graphs on the card then creates the component
export default function ProjectStatisticsTile(props: any) {
  const [finalKanbanPieData, setKanbanPieData] = useState<any[]>([]);
  const [finalTaskBarData, setTaskBarData] = useState<any[]>([]);
  const [finalProgressBarData, setProgressBarData] = useState<number>();
  const [finalEmployeeNames, setEmployeeNames] = useState<any[]>([]);

  useEffect(() => {
    if (props.currentProject !== 0) {
      fetchData();
    }
  }, [props.currentEmployee, props.currentProject]);

  async function fetchData() {
    const currentEmployee = props.currentEmployee;
    const currentProjectId = props.currentProject;

    // Api calls
    const [employeeList, taskList] = await Promise.all([
      getEmployeesByApi(),
      getTasksByApi(),
    ]);

    // Filter data
    let filteredEmployeeList = [];
    let filteredTaskList = [];
    if (currentEmployee == "all") {
      filteredEmployeeList = employeeList.data;
    } else {
      filteredEmployeeList = employeeList.data.filter(
        (employee: any) => employee.id == currentEmployee
      );
    }

    filteredTaskList = taskList.data.filter(
      (task: any) =>
        task.projectId == currentProjectId &&
        (task.employeeId == currentEmployee || currentEmployee == "all")
    );

    // Calculate data for pie chart

    let backlogTaskCount = 0,
      toDoTaskCount = 0,
      inProgTaskCount = 0,
      reviewTaskCount = 0,
      completedTaskCount = 0;

    for (let i = 0; i <= filteredTaskList.length - 1; i++) {
      switch (filteredTaskList[i].status) {
        case "backlog":
          backlogTaskCount++;
          break;
        case "todo":
          toDoTaskCount++;
          break;
        case "inProgress":
          inProgTaskCount++;
          break;
        case "review":
          reviewTaskCount++;
          break;
        case "completed":
          completedTaskCount++;
          break;
      }
    }

    const kanbanPieData = [
      {
        name: "Backlog",
        value: backlogTaskCount,
      },
      {
        name: "To-Do",
        value: toDoTaskCount,
      },
      {
        name: "In Progress",
        value: inProgTaskCount,
      },
      {
        name: "Review",
        value: reviewTaskCount,
      },
      {
        name: "Completed",
        value: completedTaskCount,
      },
    ];

    // Calculate data for progress bar
    const progressBarData = (completedTaskCount / taskList.data.length) * 100;

    // Calculate data for bar chart
    let barChartData = [];
    let employeeNames = [];
    for (let i = 0; i <= filteredEmployeeList.length - 1; i++) {
      barChartData.push({
        employee: filteredEmployeeList[i].username,
        tasks: 0,
      });
      employeeNames.push(filteredEmployeeList[i].username);

      for (let j = 0; j <= filteredTaskList.length - 1; j++) {
        if (filteredEmployeeList[i].id == filteredTaskList[j].employeeId) {
          barChartData[i].tasks++;
        }
      }
    }

    // set states for data to be used  return ProjectStats component
    setKanbanPieData(kanbanPieData);
    setTaskBarData(barChartData);
    setProgressBarData(progressBarData);
    setEmployeeNames(employeeNames);
  }

  return (
    <ProjectStats
      progressBarData={finalProgressBarData}
      kanbanPieData={finalKanbanPieData}
      taskBarData={finalTaskBarData}
      taskBarCategories={finalEmployeeNames}
      currentProject={props.currentProject}
    />
  );
}
