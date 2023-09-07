import { useEffect, useState } from "react";
import {
  getTasksByApi,
  getProjectsByApi,
  getEmployeesByApi,
} from "@/pages/analysis";
import { Title } from "@tremor/react";

// OVERVIEW: Creates scrollable list of employees with each of their designated tasks

// Creates and displays the container with a card for each employee
// Takes: array of employee objects, array of task objects
export function ProjectTaskBreakdownTile(props: any) {
  if (props.employeeList == null) {
    return <div>Loading...</div>;
  }

  let employeeList = props.employeeList;
  const taskList = props.taskList;

  // Adds a tasks property to each employee object
  employeeList.forEach((employee: any) => {
    employee.tasks = taskList.filter(
      (task: any) => employee.id === task.employeeId
    );
  });

  const getStatusBadgeClass = (status: string) => {
    switch (status) {
      case "backlog":
        return "bg-gray-400";
      case "todo":
        return "bg-blue-400";
      case "inProgress":
        return "bg-yellow-400";
      case "review":
        return "bg-purple-400";
      case "completed":
        return "bg-green-400";
      default:
        return "bg-gray-400";
    }
  };

  const allTaskBreakdown = props.employeeList.map(
    (employeeBreakdown: any) =>
      employeeBreakdown.tasks.length > 0 && (
        <div
          id="employee-id"
          className="card shadow-md p-4 bg-base-200"
          key={employeeBreakdown.id}
        >
          <h1 id="employee-name" className="mb-2 text-base-content">
            {employeeBreakdown.username}
          </h1>
          <hr />
          <div id="employee-training" className="form-control space-y-2 mt-2">
            {employeeBreakdown.tasks.map((task: any) => (
              <label
                className="label cursor-pointer p-0 flex items-center"
                key={task.id}
              >
                <span className="label-text text-base-content">
                  {task.name}
                </span>
                <div className="flex items-center ml-auto">
                  <span className={`badge ${getStatusBadgeClass(task.status)}`}>
                    {task.status}
                  </span>
                  <input
                    type="checkbox"
                    checked={task.status === "Completed"}
                    readOnly
                    className="checkbox checkbox-sm ml-2"
                  />
                </div>
              </label>
            ))}
          </div>
        </div>
      )
  );

  return (
    <div className="card flex flex-col shadow-md overflow-auto p-4 mx-4 w-1/2 bg-base-300 text-base-content">
      <Title className="mb-2 text-2xl text-base-content">Task details</Title>
      <div className="overflow-auto space-y-5">{allTaskBreakdown}</div>
    </div>
  );
}

// Fetches data and passes into component
export default function ProjectTaskBreakdown(props: any) {
  const [filteredEmployeeList, setFilteredEmployeeList] = useState<any[]>([]);
  const [filteredTaskList, setFilteredTaskList] = useState<any[]>([]);

  useEffect(() => {
    if (props.currentProject !== 0) {
      fetchData();
    }
  }, [props.currentEmployee, props.currentProject]);

  const currentEmployee = props.currentEmployee;
  const currentProjectId = props.currentProject;

  async function fetchData() {
    const [projectList, employeeList, taskList] = await Promise.all([
      getProjectsByApi(),
      getEmployeesByApi(),
      getTasksByApi(),
    ]);

    if (projectList != null && employeeList != null && taskList != null) {
      let filteredEmployeeList = [];
      let filteredTaskList = [];

      // filter employee list
      if (currentEmployee == "all") {
        filteredEmployeeList = employeeList.data;
      } else {
        filteredEmployeeList = employeeList.data.filter(
          (employee: any) => employee.id == currentEmployee
        );
      }

      // filter task list
      filteredTaskList = taskList.data.filter(
        (task: any) =>
          task.projectId == currentProjectId &&
          (task.employeeId == currentEmployee || currentEmployee == "all")
      );

      // set states for data for component
      setFilteredEmployeeList(filteredEmployeeList);
      setFilteredTaskList(filteredTaskList);
    }
  }

  if (props.currentProject == 0) {
    return (
      <div className="card flex flex-col shadow-md p-4 mx-4 w-1/2 bg-base-300 text-base-content">
        <Title className="mb-2 text-2xl text-base-content">
          Task Breakdown
        </Title>
        <div className="flex h-full justify-center items-center mb-8">
          <Title className="text-2xl text-base-content">
            No project selected
          </Title>
        </div>
      </div>
    );
  }

  return (
    <ProjectTaskBreakdownTile
      employeeList={filteredEmployeeList}
      taskList={filteredTaskList}
    />
  );
}
